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

class search implements Interfaces\Api
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
        $flags = [];

        if (isset($_GET['type']) && $_GET['type']) {
          $opts['type'] = $_GET['type'];
        } elseif (isset($pages[0]) && $pages[0]) {
          $opts['type'] = $pages[0];
        }

        switch ($opts['type']) {
          case "activities":
            $opts['type'] = 'activity';
            break;
          case "channels":
            $opts['type'] = 'user';
            $flags[] = "~";
            break;
          case "videos":
            $opts['type'] = 'object';
            $flags[] = '+subtype:"video"';
            break;
          case "images":
            $opts['type'] = 'object';
            $flags[] = '+subtype:"image"';
            break;
          case "blogs":
            $opts['type'] = 'object';
            $flags[] = '+subtype:"blog"';
            break;
          case "groups":
            $opts['type'] = 'group';
            break;
        }

        $opts['flags'] = $flags;

        if (isset($_GET['offset']) && $_GET['offset']) {
          $opts['offset'] = $_GET['offset'];
        }

        $guids = (new Documents())->query($_GET['q'], $opts);
        $response = [];

        if ($guids) {
          $response['entities'] = Factory::exportable(Core\Entities::get([
            'guids' => $guids
          ]));

          if (isset($_GET['access_token'])) {
            $response[$opts['type'] ?: 'all'][] = $response['entities'];
          }

          // TODO: Check this logic
          $response['load-next'] = (int) $_GET['offset'] + $_GET['limit'] + 1;
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
