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

class avatars implements Interfaces\FS
{
    public function get($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        $size = strtolower($pages[1]);
        if (!in_array($size, array('large', 'medium', 'small', 'tiny', 'master', 'topbar'))) {
            $size = "medium";
        }

        $filepath = "";
        $guid = null;
        $type = null;

        if (method_exists($entity, 'getType')) {
            $type = $entity->getType();
        } elseif (property_exists($entity, 'type')) {
            $type = $entity->type;
        }

        if (method_exists($entity, 'getGuid')) {
            $guid = $entity->getGuid();
        } elseif (property_exists($entity, 'guid')) {
            $guid = $entity->guid;
        }

        switch ($type) {
            case "user":
                //coming soon
                break;
            case "group":
                $f = new Entities\File();
                $f->owner_guid = $entity->owner_guid;
                $f->setFilename("groups/{$guid}{$size}.jpg");
                $filepath = $f->getFilenameOnFilestore();
                break;
            case "object":
                break;
        }

        if (!file_exists($filepath)) {
            $filepath =  Core\Config::build()->path . "front/public/assets/avatars/default-$size.png";
        }

        $finfo    = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filepath);
        finfo_close($finfo);
        header('Content-Type: '.$mimetype);
        header('Expires: ' . date('r', time() + 864000));
        header("Pragma: public");
        header("Cache-Control: public");
        //header("ETag: \"$etag\"");
        echo file_get_contents($filepath);
        exit;
    }
}
