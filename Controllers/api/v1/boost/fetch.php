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
             $boosts = Core\Boost\Factory::build($pages[0])->getBoosts(isset($_GET['limit']) ? $_GET['limit'] : 2, false);
             foreach ($boosts as $guid => $entity) {
                 $response['boosts'][] = array_merge($entity->export(), ['boosted' => true, 'boosted_guid' => (string) $guid]);
             }
             if (!$boosts) {
                 $cacher = Core\Data\cache\factory::build('apcu');
                 $offset =  $cacher->get(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset") ?: "";
                 $guids = Core\Data\indexes::fetch('object:image:featured', ['offset'=> $offset, 'limit'=> 12]);
                 if (!$guids) {
                     break;
                 }
                 $entities = Core\Entities::get(['guids'=>$guids]);
                 usort($entities, function ($a, $b) {
                     if ((int)$a->featured_id == (int) $b->featured_id) {
                         return 0;
                     }
                     return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
                 });
                 foreach ($entities as $entity) {
                      $boost = new Entities\Activity();
                      $boost->guid = $entity->guid;
                      $boost->owner_guid = $entity->owner_guid;
                      $boost->{'thumbs:up:user_guids'} = $entity->{'thumbs:up:user_guids'};
                      $boost->{'thumbs:down:user_guids'} = $entity->{'thumbs:down:user_guids'};
                      $boost->setTitle($entity->title);
                      $boost->setFromEntity($entity);
                      switch ($entity->subtype) {
                        case "blog":
                            $boost->setBlurb(strip_tags($entity->description))
                              ->setURL($entity->getURL())
                              ->setThumbnail($entity->getIconUrl());
                            break;
                        case "image":
                            $boost->setCustom('batch', [[
                              'src'=>elgg_get_site_url() . 'archive/thumbnail/'.$entity->guid,
                              'href'=> $entity->getUrl(),
                              'mature' => $entity instanceof \Minds\Interfaces\Flaggable ? $entity->getFlag('mature') : false
                            ]]);
                            break;
                      }
                      $boost->boosted = true;
                     $response['boosts'][] = $boost->export();
                 }
                 if (count($response['boosts']) < 5) {
                     $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset", "");
                 } else {
                     $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset", end($entities)->guid);
                 }
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
        $boost = Core\Boost\Factory::build($pages[0])->getBoostEntity($pages[1]);
        if (!$boost) {
            return Factory::response([
              'status' => 'error',
              'message' => 'Boost not found'
            ]);
        }
        Helpers\Counters::increment($boost->getEntity()->guid, "impression");
        Helpers\Counters::increment($boost->getEntity()->owner_guid, "impression");
        Helpers\Counters::increment((string) $boost->getId(), "boost_impressions", 1);
        Helpers\Counters::increment(0, "boost_impressions", 1);

        return Factory::response([]);
    }

    /**
     */
    public function delete($pages)
    {
    }
}
