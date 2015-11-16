<?php
/**
 * Minds Comments API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;

class comments implements Interfaces\Api
{
    /**
     * Returns the comments
     * @param array $pages
     *
     * API:: /v1/comment/:guid
     */
    public function get($pages)
    {
        $response = array();
        $guid = $pages[0];

        $indexes = new Data\indexes('comments');
        $guids = $indexes->get($guid, array('limit'=>\get_input('limit', 3), 'offset'=>\get_input('offset', ''), 'reversed'=>\get_input('reversed', false)));
        if (isset($guids[get_input('offset')])) {
            unset($guids[get_input('offset')]);
        }

        if ($guids) {
            $comments = \elgg_get_entities(array('guids'=>$guids));
        } else {
            $comments = array();
        }

        usort($comments, function ($a, $b) {
          return $a->time_created - $b->time_created;
        });
        foreach ($comments as $k => $comment) {
            if (!$comment->guid) {
                unset($comments[$k]);
                continue;
            }
            $owner = $comment->getOwnerEntity();
            $comments[$k]->ownerObj = $owner->export();
        }
        $response['comments'] = factory::exportable($comments);
        $response['load-next'] = (string) end($comments)->guid;
        $response['load-previous'] = (string) reset($comments)->guid;

        return Factory::response($response);
    }

    public function post($pages)
    {
        $response = array();

        switch ($pages[0]) {
        case "update":
          $comment = new Entities\Comment($pages[1]);
          if (!$comment->canEdit()) {
              $response = array('status' => 'error', 'message' => 'This comment can not be edited');
              break;
          }
          $comment->description = $_POST['description'];
          $comment->save();
          break;
        case is_numeric($pages[0]):
        default:
          $parent = new \Minds\Entities\Entity($pages[0]);
          if($parent instanceof Entities\Activity && $parent->remind_object)
            $parent = (object) $parent->remind_object;
          $comment = new Entities\Comment();
          $comment->description = urldecode($_POST['comment']);
          $comment->setParent($parent);
          if ($comment->save()) {
              $subscribers = Data\indexes::fetch('comments:subscriptions:'.$pages[0]) ?: array();
              $subscribers[$parent->owner_guid] = $parent->owner_guid;
              if (isset($subscribers[$comment->owner_guid])) {
                  unset($subscribers[$comment->owner_guid]);
              }

              Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $pages[0], 'comment');

              Core\Events\Dispatcher::trigger('notification', 'all', array(
                  'to' => $subscribers,
                  'entity'=>$pages[0],
                  'description'=>$comment->description,
                  'notification_view'=>'comment'
              ));

              \elgg_trigger_event('comment:create', 'comment', $data);

              $indexes = new data\indexes();
              $indexes->set('comments:subscriptions:'.$parent->guid, array($comment->owner_guid => $comment->owner_guid));
              $comment->ownerObj = Core\Session::getLoggedinUser()->export();
              $response['comment'] = $comment->export();
          } else {
              $response = array(
              'status' => 'error',
              'message' => 'The comment couldn\'t be saved'
            );
          }
      }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        $comment = new Entities\Comment($pages[0]);
        if ($comment->canEdit()) {
            $comment->delete();
        }

        return Factory::response(array());
    }
}
