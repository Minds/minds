<?php
/**
 * Minds Banners FS endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\fs\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class pages implements Interfaces\FS
{
    public function get($pages)
    {
        $root = Core\Config::_()->dataroot;
        $path = $pages[0];
        $filepath = "$root/page_banners/" . $path . ".jpg";

        if (!file_exists($filepath)) {
            exit;
        }

        $finfo    = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filepath);
        finfo_close($finfo);
        header('Content-Type: '.$mimetype);
        header('Expires: ' . date('r', time() + 864000));
        header("Pragma: public");
        header("Cache-Control: public");
        echo file_get_contents($filepath);
        exit;
    }
}
