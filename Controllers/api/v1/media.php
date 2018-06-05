<?php
/**
 * Minds Media API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Security;
use Minds\Entities;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Core\Events\Dispatcher;
use Minds\Api\Factory;

class media implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Return the media items
     * @param array $pages
     *
     * API:: /v1/media/:filter || :guid
     */
    public function get($pages)
    {
        $response = [];

        if (isset($pages[0]) && is_numeric($pages[0])) {
            Security\ACL::$ignore = true;
            $entity = Di::_()->get('Media\Repository')->getEntity($pages[0]);

            if (!$entity || Helpers\Flags::shouldFail($entity)) {
                return Factory::response(['status' => 'error']);
            }

            switch ($entity->subtype) {
                case "video":
                    Helpers\Counters::increment($pages[0], 'plays');

                    if (isset($pages[1]) && $pages[1] == 'play') {
                        http_response_code(301);

                        if ($entity->subtype == 'audio') {
                            \forward($entity->getSourceUrl('128.mp3'));
                        } else {
                            \forward($entity->getSourceUrl('360.mp4'));
                        }

                        exit;
                    }

                    $entities = Factory::exportable([ $entity ]);

                    if ($entities) {
                        $response = $entities[0];
                        $response['transcodes'] = [
                            '360.mp4' => $entity->getSourceUrl('360.mp4'),
                            '720.mp4' =>  $entity->getSourceUrl('720.mp4')
                        ];
                    }

                    if (method_exists($entity, 'getWireThreshold')) {
                        $response['paywalled'] = $entity->getFlag('paywall') && $entity->getWireThreshold();
                    }

                    if (method_exists($entity, 'canEdit')) {
                        $ignore = Security\ACL::$ignore;
                        Security\ACL::$ignore = false;
                        $response['canEdit'] = $entity->canEdit();
                        Security\ACL::$ignore = $ignore;
                    }

                    /* No break */
                default:
                    $entity->fullExport = true;
                    $response['entity'] = $entity->export();

                    if (method_exists($entity, 'getAlbumChildrenGuids')) {
                        $response['entity']['album_children_guids'] = $entity->getAlbumChildrenGuids();
                    }

                    if (method_exists($entity, 'getWireThreshold')) {
                        $response['entity']['paywalled'] = $entity->getFlag('paywall') && $entity->getWireThreshold();
                    }

                    if (method_exists($entity, 'canEdit')) {
                        $ignore = Security\ACL::$ignore;
                        Security\ACL::$ignore = false;
                        $response['entity']['canEdit'] = $entity->canEdit();
                        Security\ACL::$ignore = $ignore;
                    }
                }

        }

        return Factory::response($response);
    }

    /**
     * Update entity based on guid
     * @param array $pages
     *
     * API:: /v1/media/:guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        if (!is_numeric($pages[0])) {
            // Upload media
            try {
                $response = $this->_upload($pages[0], $_POST, [
                    'type' => $_FILES['file']['type'],
                    'file' => $_FILES['file']['tmp_name']
                ]);
            } catch (\Exception $e) {
                return Factory::response([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }

            return Factory::response($response);
        }

        // Update media metadata
        try {
            $response = $this->_update($pages[0], $_POST);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response($response);
    }

    /**
     * Upload a media file (legacy mode)
     * @param array $pages
     *
     * API:: /v1/media/:type
     */
    public function put($pages)
    {
        // @note: Legacy uploads
        switch ($pages[0]) {
            case 'video':
                $video = new Entities\Video();

                $fp = tmpfile();
                $metaDatas = stream_get_meta_data($fp);
                $tmpFilename = $metaDatas['uri'];
                $req = Helpers\Upload::parsePhpInput();
                $body = $req['body'];
                fwrite($fp, $body);
                $video->access_id = 0;
                $video->upload($tmpFilename);
                $guid = $video->save();
                fclose($fp);
                break;

            case 'image':
                $image = new Entities\Image();
                $image->batch_guid = 0;
                $image->access_id = 0;
                $guid = $image->save();
                $image->filename = "/image/$image->batch_guid/$image->guid/master.jpg";
                $fp = fopen("/tmp/{$image->guid}-master.jpg", "w");
                $req = Helpers\Upload::parsePhpInput();
                $body = $req['body'];
                fwrite($fp, $body);
                fclose($fp);

                $file = new \ElggFile(); //only using for legacy reasons
                $file->setFilename("/image/$image->batch_guid/$image->guid/master.jpg");
                $file->open('write');
                $file->write($body);
                $file->close();

                $loc = $image->getFilenameOnFilestore();
                $image->createThumbnails(null, "/tmp/{$image->guid}-master.jpg");
                $image->save();
                unlink("/tmp/{$image->guid}-master.jpg");
        }

        return Factory::response([ 'guid' => $guid, 'location' => $loc ]);
    }

    /**
     * Delete an entity
     * @param array $pages
     *
     * API:: /v1/media/:guid
     */
    public function delete($pages)
    {
        $deleted = Di::_()->get('Media\Repository')->delete($pages[0]);

        if (!$deleted) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You don\'t have permission to delete this media'
            ]);
        }

        return Factory::response([]);
    }

    /**
     * (Internal) Uploads media
     * @param mixed $guid
     * @param array $data  - POST data
     * @param array $media - Temporary [file] path and its [type]
     */
    private function _upload($clientType, array $data = [], array $media = [])
    {
        $user = Core\Session::getLoggedInUser();

        // @note: Sometimes images are uploaded as videos. Polyfill:
        $mimeIsImage = strpos($media['type'], 'image/') !== false;
        $mimeIsVideo = strpos($media['type'], 'video/') !== false;        

        if ($clientType == 'video') {
            $detectIsImage = @is_array(getimagesize($media['file']));
            $isWebApp = isset($_SERVER['HTTP_X_XSRF_TOKEN']);

            if (($detectIsImage && !$isWebApp) || $mimeIsImage) {
                $clientType = 'image';
            }
            //mobile is crazy and thinks we are all jpegs!
            try {
                $checkIsVideo = new \Minds\Core\Media\Assets\Video();
                if ($checkIsVideo->validate($media)) {
                    $clientType = 'video';
                }
            } catch (\Exception $e){}
        } elseif ($mimeIsImage) {
            $clientType = 'image';
        } elseif ($mimeIsVideo) {
            $clientType = 'video';
        }
        // - End of polyfill

        $entity = Core\Media\Factory::build($clientType);

        $container_guid = isset($data['container_guid']) && is_numeric($data['container_guid']) ? $data['container_guid'] : null;

        $entity->patch([
            'title' => isset($data['name']) ? $data['name'] : '',
            'mature' => isset($data['mature']) && !!$data['mature'],
            'batch_guid' => 0,
            'access_id' => 0,
            'owner_guid' => $user->guid,
            'hidden' => $container_guid !== null,
            'container_guid' => $container_guid
        ]);

        $assets = Core\Media\AssetsFactory::build($entity);

        $assets->validate($media);
        $entity->setAssets($assets->upload($media, $data));

        // Save initial entity
        $success = $entity->save(true);

        if (!$success) {
            throw new \Exception('Error saving media entity');
        }

        // Follow activity
        (new Core\Notification\PostSubscriptions\Manager())
            ->setEntityGuid($entity->guid)
            ->setUserGuid(Core\Session::getLoggedInUserGuid())
            ->follow();


        // Mark user as mature, if needed
        if (!$user->getMatureContent() && $entity->getFlag('mature')) {
            $user->setMatureContent(true);
            $user->save();
        }

        $location = method_exists($entity, 'getFilenameOnFilestore') ? $entity->getFilenameOnFilestore() : '';

        // Done
        return [
            'guid' => $entity->guid,
            'location' => $location
        ];
    }

    /**
     * (Internal) Updates media metadata
     * @param mixed $guid
     * @param array $data - POST data
     */
    private function _update($guid, array $data = [])
    {
        $user = Core\Session::getLoggedInUser();

        $entity = Di::_()->get('Media\Repository')->getEntity($guid);
        $entity->patch($data);

        $assets = Core\Media\AssetsFactory::build($entity);

        $entity->setAssets($assets->update($data));

        // Save and reindex
        $success = $entity->save(true);

        if (!$success) {
            throw new \Exception('Error updating media entity');
        }

        $response = [
            'guid' => $entity->guid,
            'entity' => $entity->export()
        ];

        // Mark user as mature, if needed
        if (!$user->getMatureContent() && $entity->getFlag('mature')) {
            $user->setMatureContent(true);
            $user->save();
        }

        // Create activity post
        $feeds = Di::_()->get('Media\Feeds')->setEntity($entity);

        if (
            (isset($data['access_token']) && $data['access_token']) ||
            ($entity->access_id == 0 && isset($data['access_id']) && $data['access_id'] == 2)
        ) {
            $activity = $feeds->createActivity();

            if ($activity) {
                $response['activity_guid'] = $activity->guid;
            }

            $feeds->dispatch([
                'facebook' => isset($data['facebook']) && $data['facebook'] ? $data['facebook'] : false,
                'twitter' => isset($data['twitter']) && $data['twitter'] ? true : false
            ]);
        } else {
            $feeds->updateActivities();
        }

        return $response;
    }
}
