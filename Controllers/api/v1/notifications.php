<?php
/**
 * Minds Notification API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Interfaces;
use Minds\Helpers;
use Minds\Api\Factory;
use Minds\Entities\Notification as NotificationEntity;

/**
 *
 * Notifications API
 *
 * Endpoint: /v1/notifications/
 *
 */
// @codingStandardsIgnoreStart
class notifications implements Interfaces\Api
{
    // @codingStandardsIgnoreEnd

    use \Minds\Traits\HttpMethodsInput;
    use \Minds\Traits\CurrentUser;

    const MAX_NOTIFICATIONS_PER_PAGE = 500;

    /**
     * GET method handler
     * @param  array $pages
     * @return string
     */
    public function get($pages)
    {
        //Factory::isLoggedIn();
        $response = [];

        if (!isset($pages[0])) {
            $pages = ['list'];
        }

        switch ($pages[0]) {

            case 'count':
                $response['count'] = Helpers\Notifications::getCount();
                break;

            case 'list':
            default:
                Factory::isLoggedIn();
                    $limit = (int) static::getQueryValue('limit') ?: 12;
                $offset = (string) static::getQueryValue('offset') ?: '';

                if ($limit > static::MAX_NOTIFICATIONS_PER_PAGE) {
                    $limit = static::MAX_NOTIFICATIONS_PER_PAGE;
                }

                $notifications = Helpers\Notifications::get([
                    'limit' => $limit,
                    'offset' => $offset
                ]);

                if (!$notifications) {
                    return Factory::response([]);
                }

                $response['notifications'] = Factory::exportable($notifications);

                // Formatting for legacy notification handling in frontend
                // TODO: [ignacio] refactor frontend rendering

                foreach ($response['notifications'] as $key => $data) {
                    $response['notifications'][$key]['ownerObj'] = $data['owner'];
                    $response['notifications'][$key]['fromObj'] = $data['from'];
                    $response['notifications'][$key]['from_guid'] = (string) $data['from']['guid'];

                    if ($data['entity']) {
                        $response['notifications'][$key]['entityObj'] = $data['entity'];
                    }

                    //temp mobile move
                    if(isset($_GET['access_token']) && $data['notification_view'] == 'boost_peer_request'){
                        unset($response['notifications'][$key]);
                    }
                }

                $response['load-next'] = (string) end($notifications)->getGuid();
                //$response['load-previous'] = (string) key($notifications)->getGuid();

                Helpers\Notifications::resetCounter();
                break;

        }

        return Factory::response($response);
    }

    /**
     * POST method handler
     * @param  array $pages
     * @return string
     */
    public function post($pages)
    {
        $service = static::getPostValue('service', [ 'required' => true ]);
        $passed_token = static::getPostValue('token', [ 'required' => true ]);

        $token = \Surge\Token::create([
            'service' => $service,
            'token' => $passed_token
        ]);

        (new Core\Data\Call('entities'))
            ->insert(static::getCurrentUserGuid(), [ 'surge_token' => $token ]);

        return Factory::response(array());
    }

    /**
     * Not used
     */
    public function put($pages)
    {
        return Factory::response(array());
    }

    /**
     * Not used
     */
    public function delete($pages)
    {
        return Factory::response(array());
    }
}
