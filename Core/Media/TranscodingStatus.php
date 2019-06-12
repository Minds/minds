<?php

namespace Minds\Core\Media;

use AWS\ResultInterface;
use Minds\Entities\Video;
use Minds\Core\Config;
use Minds\Core\Di\Di;

class TranscodingStatus
{
    /** @var Config $config */
    private $config;
    /** @var Video $video */
    private $video;
    /** @var array $keys */
    private $keys = [];
    /** @var string $dir */
    private $dir = 'cinemr_data';
    /** @var array $presets */
    private $presets;
    
    /**
     * @param Video video The video entity that got transcoded
     * @param ResultInterface awsResult The output of the AWS S3 listObjects on the cinemr bucket
     * @param Config config Mocked version of Config for testing, else DI'ed
     * Builds and parses the transcoding status object and provides functions for checking the results of the transcode
     */
    public function __construct(Video $video, ResultInterface $awsResult, Config $config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->video = $video;
        $this->dir = $this->config->get('transcoder')['dir'];
        $this->presets = $this->config->get('transcoder')['presets'];
        if (!isset($awsResult['Contents'])) {
            return;
        }
        $s3Contents = $awsResult['Contents'];
        $this->keys = array_column($s3Contents, 'Key');
    }

    /**
     * Looks in the list of keys for the source video
     * All transcodes upload a /source first
     * This should always be there else something went horribly wrong / got deleted.
     * 
     * @return bool whether or not a video has a source file
     */
    public function hasSource()
    {
        $needle = "{$this->dir}/{$this->video->guid}/source";

        return in_array($needle, $this->keys);
    }

    /**
     * Looks in the list of keys for the different transcodes based on the transcoder presets
     * Each successfully transcoded file will have their a key with a file named height.format.
     * 
     * @return array keys to the transcoded files
     */
    public function getTranscodes()
    {
        $transcodes = [];
        foreach ($this->presets as $preset) {
            // REGEX /1080\.(mp4|webm4)/
            $formatGroup = '('.implode('|', $preset['formats']).')';
            $pattern = "/{$preset['height']}\.{$formatGroup}/";
            $transcodes = array_merge($transcodes, preg_grep($pattern, $this->keys));
        }

        return $transcodes;
    }

    /**
     * Compares the number of transcodes to the expected presets
     * 
     * @return boolean whether or not all transcodes have been generated
     */
    public function isTranscodingComplete() {
        $transcodes = $this->getTranscodes();
        return (count($transcodes) === $this->getExpectedTranscodeCount());
    }

    /**
     * Gets the number of expected trancodes based on the preset and their available formats
     */
    public function getExpectedTranscodeCount() {
        return array_reduce($this->presets, function($count, $preset) {
            return $count + count($preset['formats']);
        }, 0);
    }

    /**
     * Looks in the list of keys for thumbnails
     * FFMpeg generates thumbnails in key/thumbnail-00000.png.
     * 
     * @return array keys to the transcoded files thumbnails
     */
    public function getThumbnails()
    {
        $pattern = "/thumbnail-[0-9]+\.png/";

        return preg_grep($pattern, $this->keys);
    }
}
