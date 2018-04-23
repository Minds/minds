<?php

/**
 * Minds S3 Torrent Builder
 *
 * @author emi
 */

namespace Minds\Core\Torrent\TorrentBuilders;

use Minds\Core\Di\Di;
use Minds\Core\Media\Services\AWS;
use Minds\Core\Media\Services\Factory;

class S3 implements TorrentBuilderInterface
{
    /** @var AWS */
    protected $aws;

    /** @var string */
    protected $key;

    /** @var string */
    protected $file;

    public function __construct($aws = null)
    {
        $this->aws = $aws ?: Factory::build('AWS');
    }

    /**
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param mixed $file
     * @return S3
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Downloads a pre-built torrent from a cinemr S3 object
     * @return bool|string
     */
    public function build()
    {
        $this->aws->setKey($this->key);
        return $this->aws->getTorrent($this->file);
    }
}
