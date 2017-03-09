<?php

namespace Minds\Core\Wire\Methods;

use Minds\Core\Di\Di;

class Factory
{

    public static function build($method)
    {
        switch (ucfirst($method)) {
          case "Points":
            return Di::_()->get('Wire\Method\Points');
          default:
            throw new \Exception("Method not found");
        }
    }

}
