<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class block extends Controller implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Return a list of your blocked users
     */
    public function get($pages)
    {
        $response = array();

        if (!isset($pages[0])) {
            $pages[0] = "list";
        }

        switch ($pages[0]) {
        case "list":
          $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
          $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

          $block = $this->di->get('Security\ACL\Block');
          $guids = $block->getBlockList(Core\Session::getLoggedinUser(), $limit, $offset);

          if ($guids) {
              $entities = Core\Entities::get(array('guids'=>$guids));
              $response['entities'] = Api\Factory::exportable($entities);
          }
          break;
        case is_numeric($pages[0]):
          $block = $this->di->get('Security\ACL\Block');
          $response['blocked'] = $block->isBlocked($pages[0]);
          break;
      }



        return Factory::response($response);
    }

    /**
     *
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * Block a user
     */
    public function put($pages)
    {
        $block = $this->di->get('Security\ACL\Block');
        $block->block($pages[0]);

        return Factory::response(array());
    }

    /**
     * UnBlock a user
     */
    public function delete($pages)
    {
        $block = $this->di->get('Security\ACL\Block');
        $block->unBlock($pages[0]);

        return Factory::response(array());
    }
}
