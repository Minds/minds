<?php
/**
 * Minds Admin: Boosts
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

class boosts implements Interfaces\Api, Interfaces\ApiAdminPam
{
    private $rate = 1;

    /**
     * Returns a list of boosts
     */
    public function get($pages)
    {
        $response = [];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
        $type = isset($pages[0]) ? $pages[0] : 'newsfeed';
        $boosts = Core\Boost\Factory::build(ucfirst($type))->getReviewQueue($limit, $offset);
        $newsfeed_count = Core\Boost\Factory::build("Newsfeed")->getReviewQueueCount();
        $suggested_count = Core\Boost\Factory::build("Suggested")->getReviewQueueCount();

        if ($boosts) {
            $response['boosts'] = Factory::exportable($boosts, ['boost_impressions', 'boost_id']);
            $response['count'] = $type == "newsfeed" ? $newsfeed_count : $suggested_count;
            $response['load-next'] = $_id;
        }

        $response['newsfeed_count'] = (int) $newsfeed_count;
        $response['suggested_count'] = (int) $suggested_count;

        return Factory::response($response);
    }

    /**
     * Approve a boost
     * @param array $pages
     */
    public function post($pages)
    {
        $response = array();

        $type = ucfirst($pages[0]);
        $guid = $pages[1];
        $action = $pages[2];
        $rating = (int) $_POST['rating'];

        if (!$guid) {
            return Factory::response(array(
              'status' => 'error',
              'message' => "We couldn't find that boost"
            ));
        }

        if (!$action) {
            return Factory::response(array(
              'status' => 'error',
              'message' => "You must provide an action: accept or reject"
            ));
        }

        $boost = Core\Boost\Factory::build($type)->getBoostEntity($guid);
        if (!$boost) {
            return Factory::response([
                'status' => 'error',
                'message' => 'boost not found'
            ]);
        }

        if ($action == 'accept') {
            $boost->setRating($rating);
            $success = Core\Boost\Factory::build($type)->accept($boost);
            if (!$success) {
                $response['status'] = 'error';
            }
        } elseif ($action == 'reject') {
            $success = Core\Boost\Factory::build($type)->reject($boost);
            if ($success) {
                Helpers\Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Boost Refund");
            } else {
                $response['status'] = 'error';
            }
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }
}
