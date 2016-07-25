<?php
/**
 * Minds Notification API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Notification;
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
        $coreNotifications = new Notification\Notifications();
        $response = [];

        if (!isset($pages[0])) {
            $pages = ['list'];
        }

        switch ($pages[0]) {

            case 'count':
                $response['count'] = $coreNotifications->getCount();
                break;
            case 'settings':
                Factory::isLoggedIn();
                $toggles = (new Settings\PushSettings())->getToggles();
                $response['toggles'] = $toggles;
                break;
            case 'single':
                $notification = $coreNotifications->getSingle($pages[1]);

                if (!$notification) {
                    return Factory::response([]);
                }
                $response['notification'] = $this->polyfillResponseStruc($notification->export());

                break;
            case 'list':
            default:
                Factory::isLoggedIn();

                $coreNotifications->resetCounter();

                $limit = (int) static::getQueryValue('limit') ?: 12;
                $offset = (string) static::getQueryValue('offset') ?: '';
                $filter = $pages[0];

                if ($filter === 'list' || $filter === 'all') {
                    $filter = '';
                }

                if ($limit > static::MAX_NOTIFICATIONS_PER_PAGE) {
                    $limit = static::MAX_NOTIFICATIONS_PER_PAGE;
                }

                $notifications = $coreNotifications->getList([
                    'limit' => $limit,
                    'offset' => $offset,
                    'filter' => $filter
                ]);

                if (!$notifications) {
                    return Factory::response([]);
                }

                $response['notifications'] = $this->polyfillResponse(Factory::exportable($notifications));
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
        if (!isset($pages[0])) {
            $pages[0] = 'token';
        }
        switch ($pages[0]) {
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

    /**
     * Polyfill notifications to be readed by legacy clients
     */
    protected function polyfillResponse($notifications)
    {
        if (!is_array($notifications)) {
            return $notifications;
        }

        // Formatting for legacy notification handling in frontend
        foreach ($notifications as $key => $data) {
            $notifications[$key] = $this->polyfillResponseStruc($data);

            //temp mobile move
            if (isset($_GET['access_token']) && $data['notification_view'] == 'boost_peer_request') {
                unset($notifications[$key]);
            }
            if (isset($_GET['access_token']) && $data['notification_view'] == 'group_activity') {
                $notifications[$key]['notification_view'] = 'custom_message';
                $notifications[$key]['params']['message'] = "@{$data['from']['username']} posted in {$data['params']['group']['name']}";
            }
        }

        return $notifications;
    }

    protected function polyfillResponseStruc($data)
    {
        $data['ownerObj'] = $data['owner'];
        $data['fromObj'] = $data['from'];
        $data['from_guid'] = (string) $data['from']['guid'];

        if ($data['entity']) {
            $data['entityObj'] = $data['entity'];
        }

        $data['legacyStruc'] = true;

        return $data;
    }
}
