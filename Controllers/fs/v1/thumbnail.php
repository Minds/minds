<?php
/**
 * Minds media page controller
 */
namespace Minds\Controllers\fs\v1;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;

class thumbnail extends Core\page implements Interfaces\page
{
    public function get($pages)
    {
        if (!$pages[0]) {
            exit;
        }

        /** @var Core\Media\Thumbnails $mediaThumbnails */
        $mediaThumbnails = Di::_()->get('Media\Thumbnails');

        Core\Security\ACL::$ignore = true;
        $size = isset($pages[1]) ? $pages[1] : null;
        $thumbnail = $mediaThumbnails->get($pages[0], $size);

        if ($thumbnail instanceof \ElggFile) {
            $thumbnail->open('read');
            $contents = $thumbnail->read();

            if (!$contents && $size) {
                // Size might not exist
                $thumbnail = $mediaThumbnails->get($pages[0], null);
                $thumbnail->open('read');
                $contents = $thumbnail->read();
            }

            header('Content-type: image/jpeg');
            header('Expires: ' . date('r', strtotime('today + 6 months')), true);
            header('Pragma: public');
            header('Cache-Control: public');
            header('Content-Length: ' . strlen($contents));

            $chunks = str_split($contents, 1024);
            foreach ($chunks as $chunk) {
                echo $chunk;
            }
        } elseif (is_string($thumbnail)) {
            \forward($thumbnail);
        }

        exit;
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
