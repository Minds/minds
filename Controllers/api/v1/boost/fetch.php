<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\boost;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class fetch implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Return a list of boosts that a user needs to review
     * @param array $pages
     */
    public function get($pages)
    {

        $response = [];

        switch ($pages[0]) {
          case 'content':
              $boosts = Core\Boost\Factory::build($pages[0])
                ->getBoosts(isset($_GET['limit']) ? $_GET['limit'] : 2);
                foreach ($boosts as $entity) {
                    $response['boosts'][] = $entity->export();
                    \Minds\Helpers\Counters::increment($entity->guid, "impression");
                    \Minds\Helpers\Counters::increment($entity->owner_guid, "impression");
                }
              break;
          case 'newsfeed':
            //$boosts = Core\Boost\Factory::build($pages[0])->getBoosts(isset($_GET['limit']) ? $_GET['limit'] : 2);
            $boosts = Core\Entities::get([
              'type'=>'activity',
              'owner_guid' => Core\Session::getLoggedinUser()->guid
            ]);
            foreach ($boosts as $entity) {
                $response['boosts'][] = $entity->export();
                //bug: sometimes views weren't being calculated on scroll down
                \Minds\Helpers\Counters::increment($entity->guid, "impression");
                \Minds\Helpers\Counters::increment($entity->owner_guid, "impression");
            }

        }

        return Factory::response($response);
    }

    /**
     */
    public function post($pages)
    {

    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {

    }

    /**
     */
    public function delete($pages)
    {

    }
}
