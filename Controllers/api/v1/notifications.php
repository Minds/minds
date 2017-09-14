<?php
/**
 * Minds Notification API
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Di\Di;
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

    const MAX_NOTIFICATIONS_PER_PAGE = 50;

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

        $repository = Di::_()->get('Notification\Repository');
        $repository->setOwner(Core\Session::getLoggedInUserGuid());

        $counters = new Notification\Counters();

        switch ($pages[0]) {

            case 'count':
                $response['count'] = $counters->getCount();
                break;
            case 'settings':
                Factory::isLoggedIn();
                $toggles = (new Settings\PushSettings())->getToggles();
                $response['toggles'] = $toggles;
                break;
            case 'single':
                $notification = $repository->getEntity($pages[1]);

                if (!$notification) {
                    return Factory::response([]);
                }

                $response['notification'] = $notification->export();
                break;
            case 'list':
            default:
                Factory::isLoggedIn();

                $counters->resetCounter();

                $limit = (int) static::getQueryValue('limit') ?: 12;
                $offset = (string) static::getQueryValue('offset') ?: '';
                $filter = $pages[0];

                if ($filter === 'list' || $filter === 'all') {
                    $filter = '';
                }

                if ($limit > static::MAX_NOTIFICATIONS_PER_PAGE) {
                    $limit = static::MAX_NOTIFICATIONS_PER_PAGE;
                }

                $notifications = $repository->getAll($filter, [
                    'limit' => $limit,
                    'offset' => $offset
                ]);

                // @polyfill: start
                if (!$filter && !$offset && count($notifications < 2)) {
                    (new Notification\Polyfills\Migration())
                        ->setOwner(Core\Session::getLoggedInUserGuid())
                        ->migrate();

                    $notifications = $repository->getAll($filter, [
                        'limit' => $limit,
                        'offset' => $offset
                    ]);
                }
                // /@polyfill

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

            //temp mobile move
            //if (isset($_GET['access_token']) && $data['notification_view'] == 'boost_peer_request') {
            //    unset($notifications[$key]);
            //}
            //if (isset($_GET['access_token']) && $data['notification_view'] == 'group_activity') {
            //    $notifications[$key]['notification_view'] = 'custom_message';
            //    $notifications[$key]['params']['message'] = "@{$data['from']['username']} posted in {$data['params']['group']['name']}";
            //}
            if (isset($_GET['access_token']) && $data['notification_view'] == 'messenger_invite') {
               $notifications[$key]['notification_view'] = 'custom_message';
               $notifications[$key]['params']['message'] = "@{$data['params']['username']} wants to chat with you!";
            }
        }

        return $notifications;
    }
}
