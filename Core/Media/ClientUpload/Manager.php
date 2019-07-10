<?php
/**
 * Client Upload, direct from browser to storage
 */
namespace Minds\Core\Media\ClientUpload;

use Minds\Core\Media\Services\FFMpeg;
use Minds\Core\GuidBuilder;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Di\Di;
use Minds\Entities\Video;

class Manager
{

    /** @var FFMepg */
    private $ffmpeg;

    /** @var Guid $guid */
    private $guid;

    /** @var Save $save */
    private $save;

    public function __construct(
        FFMpeg $FFMpeg = null,
        GuidBuilder $guid = null,
        Save $save = null
    )
    {
        $this->ffmpeg = $FFMpeg ?: Di::_()->get('Media\Services\FFMpeg');
        $this->guid = $guid ?: new GuidBuilder();
        $this->save = $save ?: new Save();
    }

    /**
     * Prepare an upload, return a lease
     * @param $type - the media type
     * @return ClientUploadLease
     */
    public function prepare($type = 'video')
    {
        if ($type != 'video') {
            throw new \Exception("$type is not currently supported for client based uploads");
        }

        $guid = $this->guid->build();

        $this->ffmpeg->setKey($guid);
        $preSignedUrl = $this->ffmpeg->getPresignedUrl();

        $lease = new ClientUploadLease();
        $lease->setGuid($guid)
            ->setMediaType($type)
            ->setPresignedUrl($preSignedUrl);

        return $lease;
    }

    /**
     * Complete the client based upload
     * @param ClientUploadLease $lease
     * @return boolean
     */
    public function complete(ClientUploadLease $lease)
    {
        if ($lease->getMediaType() !== 'video') {
            throw new \Exception("{$lease->getMediaType()} is not currently supported for client based uploads");
        }

        $video = new Video();
        $video->set('guid', $lease->getGuid());
        $video->set('cinemr_guid', $lease->getGuid());
        $video->set('access_id', 0); // Hide until published

        // Save the video
        $this->save->setEntity($video)->save();

        $this->ffmpeg->setKey($lease->getGuid());

        // Start the transcoding process
        $this->ffmpeg->transcode();

        return true;
    }

}

