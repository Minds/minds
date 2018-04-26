<?php

/**
 * Magnet URL Manager
 *
 * @author emi
 */

namespace Minds\Core\Torrent;

use Minds\Core\Torrent\TorrentBuilders\S3;
use Minds\Core\Torrent\TorrentBuilders\TorrentBuilderInterface;

class TorrentMeta
{
    /** @var mixed */
    protected $entity;

    /** @var string */
    protected $file;

    /** @var string */
    protected $source;

    /** @var string */
    protected $xs;

    /** @var TorrentBuilderInterface */
    protected $torrentBuilder;

    /** @var TorrentFile */
    protected $torrent;

    protected static $trackers = [
        'wss://tracker.openwebtorrent.com',
        'wss://tracker.btorrent.xyz',
        'wss://tracker.fastcast.nz',
        'udp://tracker.openbittorrent.com:80',
        'udp://opentor.org:2710',
        'udp://tracker.ccc.de:80',
        'udp://tracker.blackunicorn.xyz:6969',
        'udp://tracker.coppersurfer.tk:6969',
        'udp://tracker.leechers-paradise.org:6969',
    ];

    /**
     * TorrentMeta constructor.
     */
    public function __construct($torrentBuilder = null)
    {
        $this->torrentBuilder = $torrentBuilder ?: new S3();
    }

    /**
     * @param mixed $entity
     * @return TorrentMeta
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param string $file
     * @return TorrentMeta
     */
    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @param string $source
     * @return TorrentMeta
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param string $xs
     * @return TorrentMeta
     */
    public function setXs($xs)
    {
        $this->xs = $xs;
        return $this;
    }

    /**
     * Gets the file name for the torrent
     * @return string
     */
    public function getName()
    {
        $source = parse_url($this->source, PHP_URL_PATH);
        return sha1($source) . substr($source, strrpos($source, '.'));
    }

    /**
     * Creates a new TorrentFile instance
     * @return TorrentFile
     */
    public function getTorrent()
    {
        if (!$this->torrent) {
            $this->torrentBuilder
                ->setKey($this->entity->guid)
                ->setFile($this->file);

            $this->torrent = new TorrentFile($this->torrentBuilder->build());

            $this->torrent->name($this->getName());
            $this->torrent->httpseeds($this->source);
            $this->torrent->announce(static::$trackers);
        }

        return $this->torrent;
    }

    /**
     * Reads the info hash for the torrent
     * @return string
     */
    public function infoHash()
    {
        $torrent = $this->getTorrent();
        return $torrent->hash_info();
    }

    /**
     * Generates a magnet URL
     * @return string
     */
    public function magnet()
    {
        $torrent = $this->getTorrent();

        $url = $torrent->magnet(false);
        $url .= '&ws=' . urlencode($this->source);
        $url .= '&xs=' . urlencode($this->xs);

        return $url;
    }

    /**
     * Generates torrent file metadata
     * @return string
     */
    public function torrent()
    {
        $torrent = $this->getTorrent();
        $torrent->send($this->getName() . '.torrent');
    }

    /**
     * Generates torrent file metadata as a base64 string
     * @return string
     */
    public function encodedTorrent()
    {
        $torrent = $this->getTorrent();
        return base64_encode($torrent->encode($torrent));
    }
}

