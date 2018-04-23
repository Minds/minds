<?php
/**
 * Minds Torrent Builder Interface
 *
 * @author emi
 */

namespace Minds\Core\Torrent\TorrentBuilders;

interface TorrentBuilderInterface
{
    /**
     * Sets the entity key for the torrent
     * @param $key
     * @return $this
     */
    public function setKey($key);

    /**
     * Sets the media file for the torrent
     * @param $file
     * @return $this
     */
    public function setFile($file);

    /**
     * Builds a torrent file
     * @return bool|string
     */
    public function build();
}
