<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;

class AssetsFactory
{
    public static function build($entity)
    {
        $type = ucfirst($entity->subtype);

        $class = "\\Minds\\Core\\Media\\Assets\\" . $type;

        if (!class_exists($class)) {
            throw new \Exception("Unknown asset type: {$type}");
        }

        $assets = new $class();
        $assets->setEntity($entity);

        return $assets;
    }
}
