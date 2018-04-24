<?php
/**
 * Minds FFMpeg
 */

namespace Minds\Core\Media\Services;

use Aws\ElasticTranscoder\ElasticTranscoderClient;
use Aws\S3\S3Client;
use FFMpeg\FFMpeg as FFMpegClient;
use FFMpeg\FFProbe as FFProbeClient;
use FFMpeg\Filters\Video\ResizeFilter;
use Minds\Core;
use Minds\Core\Config;
use Minds\Core\Di\Di;

class FFMpeg implements ServiceInterface
{

    /** @var Queue $queue */
    private $queue;

    /** @var FFMpeg $ffmpeg */
    private $ffmpeg;

    /** @var FFProbe */
    private $ffprobe;

    /** @var Config $config */
    private $config;

    /** @var S3Client $s3 */
    private $s3;

    /** @var string $key */
    private $key;

    /** @var string $dir */
    private $dir = 'cinemr_data';

    public function __construct(
        $queue = null,
        $ffmpeg = null,
        $ffprobe = null,
        $s3 = null,
        $config = null
    )
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->queue = $queue ?: Core\Queue\Client::build();
        $this->ffmpeg = $ffmpeg ?: FFMpegClient::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'ffmpeg.threads'   => $this->config->get('transcoder')['threads'],
        ]);
        $this->ffprobe = $ffprobe ?: FFProbeClient::create([
            'ffprobe.binaries' => '/usr/bin/ffprobe',
        ]);
        $awsConfig = $this->config->get('aws');
        $opts = [
            'region' => $awsConfig['region']
        ];

        if (!isset($awsConfig['useRoles']) || !$awsConfig['useRoles']) {
            $opts['credentials'] = [
                'key' => $awsConfig['key'],
                'secret' => $awsConfig['secret'],
            ];
        }

        $this->s3 = $s3 ?: new S3Client(array_merge([ 'version' => '2006-03-01' ], $opts));
        $this->dir = $this->config->get('transcoder')['dir'];
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function saveToFilestore($file)
    {
        try {
            if (is_string($file)) {
                
                $result =  $this->s3->putObject([
                  'ACL' => 'public-read',
                  'Bucket' => 'cinemr',
                  'Key' => "$this->dir/$this->key/source",
                  //'ContentLength' => $_SERVER['CONTENT_LENGTH'],
                  //'ContentLength' => filesize($file),
                  'Body' => fopen($file, 'r'),
                  ]);
                return $this;

            } elseif (is_resource($file)) {

                $result =  $this->client->putObject([
                  'ACL' => 'public-read',
                  'Bucket' => 'cinemr',
                  'Key' => "$this->dir/$this->key/source",
                  'ContentLength' => $_SERVER['CONTENT_LENGTH'],
                  'Body' => $file
                ]);
                return $this;

            }
        } catch (\Exception $e) {
            var_dump($e->getMessage()); exit;
        }
        throw new \Exception('Sorry, only strings and stream resource are accepted');
    }

    /**
     * Queue the video to be transcoded
     * @return $this
     */
    public function transcode()
    {
        //queue for transcoding
        $this->queue
            ->setQueue('Transcode')
            ->send([
                "key" => $this->key
            ]);
        return $this;
    }

    /**
     * Called when the queue is running
     */
    public function onQueue()
    {
        $sourcePath = tempnam(sys_get_temp_dir(), $this->key);

        //download the file from s3
        $this->s3->getObject([
            'Bucket' => 'cinemr',
            'Key' => "$this->dir/$this->key/source",
            'SaveAs' => $sourcePath,
        ]);

        $video = $this->ffmpeg->open($sourcePath);

        try {
            $thumbnailsDir = $sourcePath . '-thumbnails';
            @mkdir($thumbnailsDir, 0600, true);
            
            //create thumbnails
            $length = round((int) $this->ffprobe->format($sourcePath)->get('duration'));
            $secs = [ 0, 1, round($length/2), $length -1, $length ];
            foreach ($secs as $sec) {
                $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
                $pad = str_pad($sec, 5, '0', STR_PAD_LEFT);
                $path = $thumbnailsDir . '/' . "thumbnail-$pad.png";
                $frame->save($path);
                @$this->uploadTranscodedFile($path, "thumbnail-$pad.png");
                //cleanup uploaded file
                @unlink($path);
            }

            //cleanup thumbnails director
            @unlink($thumbnailsDir);
        } catch (\Exception $e)  {
        }

        $outputs = [];
        $presets = $this->config->get('transcoder')['presets'];
        foreach ($presets as $prefix => $opts) {
            $opts = array_merge([
                'bitrate' => null,
                'audio_bitrate' => null,
                'prefix' => null,
                'width' => '720',
                'height' => '480',
                'formats' => [ 'mp4', 'webm' ],
            ], $opts);

            $video->filters()
                ->resize(new \FFMpeg\Coordinate\Dimension($opts['width'], $opts['height']),
                    ResizeFilter::RESIZEMODE_SCALE_WIDTH)
                ->synchronize();

            $formatMap = [
                'mp4' => (new \FFMpeg\Format\Video\X264())
                    ->setAudioCodec("aac"),
                'webm' => new \FFMpeg\Format\Video\WebM(),
            ];

            foreach ($opts['formats'] as $format) {
                $pfx = $opts['height'] . "." . $format;
                $path = $sourcePath . '-' . $pfx;
                try {
                    echo "\nTranscoding: $path ($this->key)";
                    $formatMap[$format]
                        ->setKiloBitRate($opts['bitrate'])
                        ->setAudioChannels(2)
                        ->setAudioKiloBitrate($opts['audio_bitrate']);
                    $video->save($formatMap[$format], $path);

                    //now upload to s3
                    $this->uploadTranscodedFile($path, $pfx);
                    //cleanup tmp file
                    @unlink($path);
                } catch (\Exception $e) {
                    echo " failed {$e->getMessage()}";
                }
            }
        }

        //cleanup original file
        @unlink($sourcePath);

        return $this;
    }

    protected function uploadTranscodedFile($path, $prefix)
    {
        return $this->s3->putObject([
            'ACL' => 'public-read',
            'Bucket' => 'cinemr',
            'Key' => "$this->dir/$this->key/$prefix",
            //'ContentLength' => $_SERVER['CONTENT_LENGTH'],
            //'ContentLength' => filesize($file),
            'Body' => fopen($path, 'r'),
        ]);
    }

}
