<?php
/**
 * Minds Categories Featured API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\categories;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class featured implements Interfaces\Api
{
    /**
     * Returns the entities
     * @param array $pages
     *
     */
    public function get($pages)
    {

        $repository = Di::_()->get('Categories\Repository');
        $repository->setFilter('featured');

        if (isset($_GET['categories'])) {
            $repository->setCategories(explode(',', $_GET['categories']));
        }

        switch($pages[0]){
          case "object":
              $repository->setType($pages[1]);
              break;
          case "channel":
          case "channels":
          case "user":
          case "users":
          default:
            $repository->setType('user');
        }

        $guids = $repository->get();

        if (!$guids) {
            return Factory::response(['status'=>'error', 'message'=>'not found']);
        }

        $options = [
          'guids'=>$guids
        ];
        $entities = core\Entities::get($options);

        usort($entities, function ($a, $b) {
            if ((int)$a->featured_id == (int) $b->featured_id) {
                return 0;
            }
            return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
        });

        if ($entities) {
            $response['entities'] = Factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->featured_id;
            $response['load-previous'] = (string) key($entities)->featured_id;
        }

        return Factory::response($response);
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
