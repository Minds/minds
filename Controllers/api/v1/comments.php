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
use Minds\Core\Sockets;

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
        //Factory::isLoggedIn();
        $response = array();
        $guid = $pages[0];

        $indexes = new Data\indexes('comments');
        $limit = \get_input('limit', 3);
        $guids = $indexes->get($guid, array('limit'=>$limit, 'offset'=>\get_input('offset', ''), 'reversed'=>\get_input('reversed', false)));
        if (isset($guids[get_input('offset')]) && $limit > 1) {
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
        $response['socketRoomName'] = "comments:{$guid}";

        return Factory::response($response);
    }

    public function post($pages)
    {
        $response = array();
        $error = false;
        $emitToSocket = false;

        switch ($pages[0]) {
          case "update":
            $comment = new Entities\Comment($pages[1]);
            if (!$comment->canEdit()) {
                $response = array('status' => 'error', 'message' => 'This comment can not be edited');
                break;
            }

            $content = $_POST['comment'];

            // Odd fallback so we don't break mobile apps editing
            if (!$_POST['title'] && $_POST['description']) {
                $content = $_POST['description'];
            }

            if(!$content && !$_POST['attachment_guid']){
              return Factory::response([
                'status' => 'error',
                'message' => 'You must enter a message'
              ]);
            }

            $comment->description = urldecode($content);

            if (isset($_POST['mature'])) {
                $comment->setMature($_POST['mature']);
            }

            $error = !$comment->save();
            break;
          case is_numeric($pages[0]):
          default:
            $parent = new \Minds\Entities\Entity($pages[0]);
            if ($parent instanceof Entities\Activity && $parent->remind_object) {
                $parent = (object) $parent->remind_object;
            }
            if(!$_POST['comment'] && !$_POST['attachment_guid']){
              return Factory::response([
                'status' => 'error',
                'message' => 'You must enter a message'
              ]);
            }

            $comment = new Entities\Comment();
            $comment->description = urldecode($_POST['comment']);
            $comment->setParent($parent);
            $comment->setMature(isset($_POST['mature']) && !!$_POST['mature']);

            if ($comment->save()) {
                $subscribers = Data\indexes::fetch('comments:subscriptions:'.$pages[0]) ?: array();
                $subscribers[$parent->owner_guid] = $parent->owner_guid;
                if (isset($subscribers[$comment->owner_guid])) {
                    unset($subscribers[$comment->owner_guid]);
                }

                if($parent->owner_guid != Core\Session::getLoggedinUser()->guid)
                    Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $pages[0], 'comment');

                Core\Events\Dispatcher::trigger('notification', 'all', array(
                    'to' => $subscribers,
                    'entity'=>$pages[0],
                    'description'=>$comment->description,
                    'notification_view'=>'comment'
                ));

                // Defer emitting after processing attachments
                $emitToSocket = true;

                \elgg_trigger_event('comment:create', 'comment', $data);

                $indexes = new data\indexes();
                $indexes->set('comments:subscriptions:'.$parent->guid, array($comment->owner_guid => $comment->owner_guid));
                $comment->ownerObj = Core\Session::getLoggedinUser()->export();
                $response['comment'] = $comment->export();
            } else {
                $error = true;

                $response = array(
                  'status' => 'error',
                  'message' => 'The comment couldn\'t be saved'
                );
            }
        }

        $modified = false;

        if (!$error && isset($_POST['title']) && $_POST['title']) {
            $comment->setTitle(urldecode($_POST['title']))
                ->setBlurb(urldecode($_POST['description']))
                ->setURL(\elgg_normalize_url(urldecode($_POST['url'])))
                ->setThumbnail(urldecode($_POST['thumbnail']));

            $modified = true;
        }

        if (!$error && isset($_POST['attachment_guid']) && $_POST['attachment_guid']) {
            $attachment = entities\Factory::build($_POST['attachment_guid']);

            if ($attachment) {
                $attachment->title = $comment->description;
                $attachment->access_id = 2;

                if ($attachment instanceof \Minds\Interfaces\Flaggable) {
                  $attachment->setFlag('mature', $comment->getMature());
                }

                $attachment->save();

                switch($attachment->subtype){
                  case "image":
                    $comment->setCustom('batch', [[
                      'src'=>elgg_get_site_url() . 'archive/thumbnail/'.$attachment->guid,
                      'href'=>elgg_get_site_url() . 'archive/view/'.$attachment->container_guid.'/'.$attachment->guid,
                      'mature'=>$attachment instanceof \Minds\Interfaces\Flaggable ? $attachment->getFlag('mature') : false
                    ]]);
                    break;
                  case "video":
                    $comment->setCustom('video', [
                      'thumbnail_src'=>$attachment->getIconUrl(),
                      'guid'=>$attachment->guid,
                      'mature'=>$attachment instanceof \Minds\Interfaces\Flaggable ? $attachment->getFlag('mature') : false
                    ]);
                    break;
                }

                $comment->setAttachmentGuid($attachment->guid);
                $modified = true;
            }
        }

        if ($modified) {
            $comment->save();
            $response['comment'] = $comment->export();
        }

        // Emit at the end because of attachment processing
        if ($emitToSocket) {
            try {
                (new Sockets\Events())
                ->setRoom("comments:{$pages[0]}")
                ->emit('comment', $pages[0], (string) $comment->owner_guid, (string) $comment->guid);
            } catch (\Exception $e) { /* TODO: To log or not to log */ }
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
