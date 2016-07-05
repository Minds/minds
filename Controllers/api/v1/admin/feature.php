<?php
/**
 * Minds Admin: Feature
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class feature implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     *
     */
    public function get($pages)
    {
        $response = array();
        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response(array());
    }

    /**
     * Feature a post
     * @param array $pages
     */
    public function put($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response([
              'status' => 'error',
              'message' => "Entity not found"
            ]);
        }
        if (!$entity->featured_id || $entity->featured_id == 0) {
            $entity->feature();
            $newsfeed = true;

/*            $activity = new Entities\Activity();
            switch ($entity->subtype) {
          case 'blog':
            $activity->setTitle($entity->title)
                ->setBlurb($entity->excerpt)
                ->setUrl($entity->getURL())
              ->setThumbnail($entity->getIconURL());
            break;
          case 'video':
            $activity->setFromEntity($entity)
              ->setCustom('video', array(
                  'thumbnail_src'=>$entity->getIconUrl(),
                  'guid'=>$entity->guid))
              ->setTitle($entity->title)
              ->setBlurb($entity->description);
            break;
          case 'image':
            $activity->setFromEntity($entity)
             ->setCustom('batch', array(array('src'=>elgg_get_site_url() . 'archive/thumbnail/'.$entity->guid, 'href'=>elgg_get_site_url() . 'archive/view/'.$entity->container_guid.'/'.$entity->guid)))
              ->setTitle($entity->title);
            break;
          default:
            $newsfeed = false;
        }

            if ($newsfeed) {
                $activity->owner_guid = $entity->owner_guid;
                $activity->indexes = array('activity:featured');
                $activity->save();
            }

            $to_guid = $entity->getOwnerGuid();
            $user = get_user_by_username('minds');
            Core\Events\Dispatcher::trigger('notification', 'all', array(
          'to' => array($to_guid),
          'from'=> 100000000000000519,
            'entity'=>$entity,
            'description'=>$message,
            'notification_view'=>'feature'
        ));*/
        } else {
            $entity->unFeature();
        }
        $entity->save();

        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(array(
          'status' => 'error',
          'message' => "Entity not found"
        ));
        }

        $entity->unFeature();
        $entity->save();

        return Factory::response(array());
    }
}
