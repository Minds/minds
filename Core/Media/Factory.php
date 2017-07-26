<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;

class Factory
{
    private static $allowed = [
        'Image',
        'Video'
    ];

    public static function build($clientType)
    {
        $type = ucfirst($clientType);

        if (!in_array($type, static::$allowed)) {
            throw new \Exception("Unknown entity type: {$type}");
        }

        $class = "\\Minds\\Entities\\" . $type;
        return new $class();
    }
}
