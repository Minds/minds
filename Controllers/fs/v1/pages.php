<?php
/**
 * Minds Banners FS endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\fs\v1;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class pages implements Interfaces\FS
{
    public function get($pages)
    {
        $path = $pages[0];
        $fs = Di::_()->get('Storage');
        $dir = Di::_()->get('Config')->get('staticStorageFolder') ?: 'pages';

        $fs->open("$dir/page_banners/{$path}.jpg", 'redirect');
        $fs->read();
    }
}
