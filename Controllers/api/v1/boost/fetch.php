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
                //bug: sometimes views weren't being calculated on scroll down
                //\Minds\Helpers\Counters::increment($entity->guid, "impression");
                //\Minds\Helpers\Counters::increment($entity->owner_guid, "impression");
            }
            if(!$boosts){
                $cacher = Core\Data\cache\factory::build('apcu');
                $offset =  $cacher->get(Core\Session::getLoggedinUser()->guid . ":newsfeed-blog-boost-offset") ?: "";
                $guids = Core\Data\indexes::fetch('object:blog:featured', ['offset'=> $offset, 'limit'=> 12]);
                if (!$guids) {
                    break;
                }
                $blogs = Core\Entities::get(['guids'=>$guids]);
                usort($blogs, function ($a, $b) {
                    if ((int)$a->featured_id == (int) $b->featured_id) {
                        return 0;
                    }
                    return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
                });
                foreach($blogs as $blog){
                    $boost = (new Entities\Activity())->setTitle($blog->title)
                          ->setGuid($blog->guid)
                          ->setBlurb(strip_tags($blog->description))
                          ->setURL($blog->getURL())
                          ->setThumbnail($blog->getIconUrl())
                          ->setFromEntity($blog)
                          ->export();
                    $boost['boosted'] = true;
                    $response['boosts'][] = $boost;
                }
                if(count($response['boosts']) < 5){
                    $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-blog-boost-offset", "");
                } else {
                    $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-blog-boost-offset", end($blogs)->featured_id);
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
        if(!$boost){
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
