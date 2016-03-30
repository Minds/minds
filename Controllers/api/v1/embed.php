<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;

class embed implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        define('__MINDS_CONTEXT__', 'embed');

        if (!$pages[0]) {
            $embedded_entity = null;
        } else {
            $embedded_entity = EntitiesFactory::build($pages[0]);

            if ($embedded_entity) {
                $embedded_entity = $embedded_entity->export();
            }
        }

        include dirname(__MINDS_ROOT__) . implode(DIRECTORY_SEPARATOR, [ '', 'front', 'public', 'index.php' ]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
