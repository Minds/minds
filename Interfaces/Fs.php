<?php
namespace Minds\Interfaces;

/**
 * Interface for "FS Controllers"-like objects
 * @todo Rename to FsControllerInterface
 */
interface Fs
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages);
}
