<?php
/**
 * Minds Comments API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Api\Exportable;
use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Exceptions\BlockedUserException;
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

        $repository = new Core\Comments\Repository();

        $comments = $repository->getList([
            'entity_guid' => $guid,
            'parent_guid' => 0,
            'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 5,
            'offset' => isset($_GET['offset']) ? $_GET['offset'] : null,
            'descending' => true,
        ]);

        if (!isset($_GET['reversed']) || $_GET['reversed'] || $_GET['reversed'] === 'false') {
            // Reversed order output
            $comments = $comments->reverse();
        }

        $response['comments'] = Exportable::_($comments);
        $response['load-previous'] = (string) $comments->getPagingToken();

        $response['socketRoomName'] = "comments:{$guid}";

        return Factory::response($response);
    }

    public function post($pages)
    {
        $manager = new Core\Comments\Manager();

        $response = array();
        $error = false;
        $emitToSocket = false;

        switch ($pages[0]) {
          case "update":
            $comment = $manager->getByLuid($pages[1]);
            if (!$comment || !$comment->canEdit()) {
                $response = array('status' => 'error', 'message' => 'This comment can not be edited');
                break;
            }

            $content = $_POST['comment'];

            // Odd fallback so we don't break mobile apps editing
            if (!$_POST['title'] && $_POST['description']) {
                $content = $_POST['description'];
            }

            if (!$content && !$_POST['attachment_guid']) {
                return Factory::response([
                'status' => 'error',
                'message' => 'You must enter a message'
              ]);
            }

            $comment->setBody($content);

            if (isset($_POST['mature'])) {
                $comment->setMature(!!$_POST['mature']);
            }

            $comment->setTimeUpdated(time());
            $comment->setEdited(true);

            try {
                $saved = $manager->update($comment);
                $error = !$saved;
            } catch (\Exception $e) {
                $error = true;
            }

            break;
          case is_numeric($pages[0]):
          default:
            $entity = new \Minds\Entities\Entity($pages[0]);

            if ($entity instanceof Entities\Activity && $entity->remind_object) {
                $entity = (object) $entity->remind_object;
            }

            if (!$pages[0] || !$entity || $entity->type == 'comment') {
                return Factory::response([
                  'status' => 'error',
                  'message' => 'We could not find that post'
                ]); 
            }

            if (!$_POST['comment'] && !$_POST['attachment_guid']) {
                return Factory::response([
                  'status' => 'error',
                  'message' => 'You must enter a message'
                ]);
            }

            if ($entity instanceof Entities\Activity && !$entity->commentsEnabled) {
                return Factory::response([
                  'status' => 'error',
                  'message' => 'Comments are disabled for this post'
                ]);
            }

            $comment = new Core\Comments\Comment();
            $comment
                ->setEntityGuid($entity->guid)
                ->setParentGuid(0)
                ->setMature(isset($_POST['mature']) && $_POST['mature'])
                ->setOwnerObj(Core\Session::getLoggedInUser())
                ->setContainerGuid(Core\Session::getLoggedInUserGuid())
                ->setTimeCreated(time())
                ->setTimeUpdated(time())
                ->setBody($_POST['comment']);

            // TODO: setHasChildren (for threaded)

            try {
                $saved = $manager->add($comment);

                if ($saved) {
                    // Defer emitting after processing attachments
                    $emitToSocket = true;
                    $response['comment'] = $comment->export();
                } else {
                    throw new \Exception('The comment couldn\'t be saved');
                }
            } catch (BlockedUserException $e) {
                $error = true;

                $parentOwnerUsername = '';

                if (isset($entity->ownerObj['username'])) {
                    $parentOwnerUsername = "@{$entity->ownerObj['username']}";
                }

                $response = [
                    'status' => 'error',
                    'message' => "The comment couldn't be saved because {$parentOwnerUsername} has blocked you."
                ];
            } catch (\Exception $e) {
                $error = true;

                $response = [
                    'status' => 'error',
                    'message' => "The comment couldn't be saved"
                ];
            }
        }

        $modified = false;

        if (!$error && isset($_POST['title']) && $_POST['title']) {
            $comment->setAttachment('title', $_POST['title']);
            $comment->setAttachment('blurb', $_POST['description']);
            $comment->setAttachment('perma_url', Helpers\Url::normalize($_POST['url']));
            $comment->setAttachment('thumbnail_src', $_POST['thumbnail']);

            $modified = true;
        }

        if (!$error && isset($_POST['attachment_guid']) && $_POST['attachment_guid']) {
            $attachment = entities\Factory::build($_POST['attachment_guid']);

            if ($attachment) {
                $attachment->title = $comment->getBody();
                $attachment->access_id = $comment->getAccessId();

                $mature = false;

                if ($attachment instanceof \Minds\Interfaces\Flaggable) {
                    $mature = !!$comment->isMature();

                    $attachment->setFlag('mature', $mature);
                }

                $attachment->save();

                $siteUrl = Core\Di\Di::_()->get('Config')->get('site_url');

                switch ($attachment->subtype) {
                    case "image":
                        $comment->setAttachment('custom_type', 'image');
                        $comment->setAttachment('custom_data', [
                            'guid' => (string) $attachment->guid,
                            'container_guid' => (string) $attachment->container_guid,
                            'src'=> $siteUrl . 'fs/v1/thumbnail/' . $attachment->guid,
                            'href'=> $siteUrl . 'media/' . $attachment->container_guid . '/' . $attachment->guid,
                            'mature' => $mature,
                            'width' => $attachment->width,
                            'height' => $attachment->height,
                        ]);
                        break;

                    case "video":
                        $comment->setAttachment('custom_type', 'video');
                        $comment->setAttachment('custom_data', [
                            'guid' => (string) $attachment->guid,
                            'container_guid' => (string) $attachment->container_guid,
                            'thumbnail_src' => $attachment->getIconUrl(),
                            'mature' => $mature
                        ]);
                        break;
                }

                $comment->setAttachment('attachment_guid', $attachment->guid);
                $modified = true;
            }
        }

        if ($modified) {
            $manager->update($comment);
            $response['comment'] = $comment->export();
        }

        // Emit at the end because of attachment processing
        if ($emitToSocket) {
            try {
                (new Sockets\Events())
                ->setRoom("comments:{$comment->getEntityGuid()}")
                ->emit(
                    'comment',
                    (string) $comment->getEntityGuid(),
                    (string) $comment->getOwnerGuid(),
                    (string) $comment->getLuid()
                );
            } catch (\Exception $e) { }
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        $manager = new Core\Comments\Manager();

        $comment = $manager->getByLuid($pages[0]);

        if ($comment && $comment->canEdit()) {
            $manager->delete($comment);
            return Factory::response([]);
        }
        //check if owner of activity trying to remove
        $entity = new \Minds\Entities\Entity($comment->getEntityGuid());
        if ($entity->owner_guid === Core\Session::getLoggedInUserGuid()) {
            $manager->delete($comment);
        }

        return Factory::response([]);
    }
}
