<?php
/**
 * Minds Media Albums API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\media;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class albums implements Interfaces\Api
{

    /**
     * Return the media items
     * @param array $pages
     *
     * API:: /v1/media/albums || :guid
     */
    public function get($pages)
    {
        $response = [];

        if (!isset($pages[0])) {
            $pages = ['list'];
        }

        switch ($pages[0]) {
            case "list":
                $owner_guid = isset($pages[1]) && is_numeric($pages[1]) ? $pages[1] : Core\Session::getLoggedInUser()->guid;

                $entities = Di::_()->get('Media\Albums')->getAll($owner_guid, [
                    'createDefault' => true
                ]);

                break;
            case "children":
            default:
                if (is_numeric($pages[0])) {
                    $album_guid = $pages[0];
                } else {
                    $album_guid = $pages[1];
                }

                $entities = Di::_()->get('Media\Albums')->getChildren($album_guid, [
                    'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
                    'offset' => isset($_GET['offset']) ? $_GET['offset'] : ""
                ]);
        }

        if ($entities) {
            $response['entities'] = Factory::exportable($entities);
            $response['load-next'] = $entities ? (string) end($entities)->guid : '';
            $response['load-previous'] = $entities ? (string) reset($entities)->guid : '';
        }

        return Factory::response($response);
    }

    /**
     * Create or add to an album
     * @param array $pages
     *
     * API:: /v1/media/album | :guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        $albums = Di::_()->get('Media\Albums');

        if (!isset($pages[0])) {
            $album = $albums->create([
                'title' => $_POST['title']
            ]);

            return Factory::response([
              'guid' => $album->guid,
              'album' => $album->export()
            ]);
        }

        $entity_guids = $_POST['guids'];
        $guids = [];
        foreach ($entity_guids as $guid) {
            $guids[$guid] = time();
        }

        $albums->addChildren($pages[0], $guids);

        return Factory::response([]);
    }

    /**
     * PUT Method
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Delete an album
     * @param array $pages
     *
     * API:: /v1/media/album/:guid
     */
    public function delete($pages)
    {
        Di::_()->get('Media\Albums')->delete($pages[0]);

        return Factory::response([]);
    }
}
