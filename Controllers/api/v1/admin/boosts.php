<?php
/**
 * Minds Admin: Boosts
 *
 * @version 1
 * @author Mark Harding
 *
 */

namespace Minds\Controllers\api\v1\admin;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;

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
        $content_count = Core\Boost\Factory::build("Content")->getReviewQueueCount();

        if ($boosts) {
            $response['boosts'] = Factory::exportable($boosts['data'], ['boost_impressions', 'boost_id']);
            $response['count'] = $type == "newsfeed" ? $newsfeed_count : $content_count;
            $response['load-next'] = $boosts['next'];
        }

        $response['newsfeed_count'] = (int) $newsfeed_count;
        $response['content_count'] = (int) $content_count;

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
        $rating = (int)$_POST['rating'];
        $quality = (int)$_POST['quality'];
        $reason = $_POST['reason'];
        $mature = isset($_POST['mature']) ? $_POST['mature'] : 0;

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

        $entity = $boost->getEntity();
        // explicit
        if($reason == 1 || $mature) {
            $dirty = $this->enableMatureFlag($entity);
        }

        if ($action == 'accept') {
            $boost->setRating($rating);
            $boost->setQuality($quality);

            $success = Di::_()->get('Boost\Payment')->charge($boost);

            if ($success) {
                Core\Boost\Factory::build($type)->accept($boost);
            } else {
                $response['status'] = 'error';
            }
        } elseif ($action == 'reject') {
            $boost->setRejectionReason($reason);

            $dirty = $this->enableBoostRejectionReasonFlag($entity, $reason) || $dirty;

            $success = Core\Boost\Factory::build($type)->reject($boost);
            if ($success) {
                Di::_()->get('Boost\Payment')->refund($boost);
            } else {
                $response['status'] = 'error';
            }
        }

        if ($dirty) {
            $entity->save();
        }

        return Factory::response($response);
    }

    protected function enableBoostRejectionReasonFlag($entity = null, $reason = -1)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $dirty = false;

        // Main boost rejection reason flag
        if (method_exists($entity, 'setBoostRejectionReason')) {
            $entity->setBoostRejectionReason($reason);
            $dirty = true;
        } elseif (property_exists($entity, 'boost_rejection_reason')) {
            $entity->boost_rejection_reason = true;
            $dirty = true;
        }

        return $dirty;
    }

    /**
     * Enabled the maturity flag for an entity
     * @param  mixed $entity
     * @return boolean
     */
    protected function enableMatureFlag($entity = null)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $dirty = false;

        // Main mature flag
        if (method_exists($entity, 'setMature')) {
            $entity->setMature(true);
            $dirty = true;
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('mature', true);
            $dirty = true;
        } elseif (property_exists($entity, 'mature')) {
            $entity->mature = true;
            $dirty = true;
        }

        // Custom Data
        if (method_exists($entity, 'setCustom') && $report['object']['custom_data'] && is_array($report['object']['custom_data'])) {
            $custom_data = $report['object']['custom_data'];

            if (isset($custom_data[0])) {
                $custom_data[0]['mature'] = true;
            } else {
                $custom_data['mature'] = true;
            }

            $entity->setCustom($report['object']['custom_type'], $custom_data);
            $dirty = true;
        }

        return $dirty;
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
