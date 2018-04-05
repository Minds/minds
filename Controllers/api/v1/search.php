<?php
/**
 * Minds Core Search API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Search\Documents;

class search implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
            'status' => 'error',
            'message' => 'Missing query'
          ]);
        }

        $opts = [
          'limit' => $_GET['limit'] ?: 12
        ];
        $returnKey = 'all';

        if (isset($_GET['type']) && $_GET['type']) {
            $returnKey = $_GET['type'];
        } elseif (isset($pages[0]) && $pages[0]) {
            $returnKey = $pages[0];
        }

        switch ($returnKey) {
            case "activities":
                $returnKey = 'activity';
                $opts['type'] = 'activity';
                break;
            case "channels":
                $returnKey = 'user';
                $opts['type'] = 'user';
                break;
            case "videos":
                $returnKey = 'object';
                $opts['type'] = 'object:video';
                break;
            case "images":
                $returnKey = 'object';
                $opts['type'] = 'object:image';
                break;
            case "blogs":
                $returnKey = 'object';
                $opts['type'] = 'object:blog';
                break;
            case "groups":
                $returnKey = 'group';
                $opts['type'] = 'group';
                break;
        }

        if (isset($_GET['container'])) {
            $opts['container'] = $_GET['container'];
        }

        if (isset($_GET['offset']) && $_GET['offset']) {
            $opts['offset'] = $_GET['offset'];
        }

        if (isset($_GET['mature']) && $_GET['mature']) {
            $opts['mature'] = (bool) $_GET['mature'];
        }

        $guids = (new Documents())->query($_GET['q'], $opts);
        $response = [];

        if ($guids) {
            $response['entities'] = Factory::exportable(Core\Entities::get([
            'guids' => $guids
          ]));

            if (isset($_GET['access_token'])) {
                $response[$returnKey ?: 'all'][] = $response['entities'];
            }

          $response['load-next'] = (int) $_GET['offset'] + $_GET['limit'];
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
