<?php
/**
 * Minds Notification API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Notification\Settings;
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
        Factory::isLoggedIn();
        $response = [];

        if (!isset($pages[0])) {
            $pages = ['list'];
        }

        switch ($pages[0]) {

            case 'count':
                $response['count'] = Helpers\Notifications::getCount();
                break;
            case 'settings':
                Factory::isLoggedIn();
                $toggles = (new Settings\PushSettings())->getToggles();
                $response['toggles'] = $toggles;
                break;
            case 'single':
                $notifications = Helpers\Notifications::get([
                    'reversed' => false,
                    'limit' => 1,
                    'offset' => $pages[1]
                ]);

                if (!$notifications) {
                    return Factory::response([]);
                }

                $response['notification'] = Factory::exportable($notifications)[0];

                break;
            case 'list':
            default:
                Factory::isLoggedIn();

                Helpers\Notifications::resetCounter();

                $limit = (int) static::getQueryValue('limit') ?: 12;
                $offset = (string) static::getQueryValue('offset') ?: '';
                $filter = $pages[0];

                if ($filter === 'list' || $filter === 'all') {
                    $filter = '';
                }

                if ($limit > static::MAX_NOTIFICATIONS_PER_PAGE) {
                    $limit = static::MAX_NOTIFICATIONS_PER_PAGE;
                }

                $notifications = Helpers\Notifications::get([
                    'limit' => $limit,
                    'offset' => $offset,
                    'filter' => $filter
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
        if(!isset($pages[0])){
            $pages[0] = 'token';
        }
        switch($pages[0]){
            case "settings":
                $settings = new Settings\PushSettings();
                $settings->setToggle($_POST['id'], $_POST['toggle'])
                  ->save();
                break;
            case "token":
                $service = static::getPostValue('service', [ 'required' => true ]);
                $passed_token = static::getPostValue('token', [ 'required' => true ]);

                $token = \Surge\Token::create([
                    'service' => $service,
                    'token' => $passed_token
                ]);

                (new Core\Data\Call('entities'))
                    ->insert(static::getCurrentUserGuid(), [ 'surge_token' => $token ]);
            break;
        }

        return Factory::response([]);
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
