<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Security;
use Minds\Core\Queue;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;

class subscribe implements Interfaces\Api
{
    /**
     * Returns the entities
     * @param array $pages
     *
     * API:: /v1/subscribe/subscriptions/:guid or /v1/subscribe/subscribers/:guid
     */
    public function get($pages)
    {
        $response = array();

        switch ($pages[0]) {
            case 'subscriptions':
                $db = new \Minds\Core\Data\Call('friends');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                if (!$subscribers) {
                    return Factory::response([]);
                }
                $users = array();
                foreach ($subscribers as $guid => $subscriber) {
                    if ($guid == get_input('offset')) {
                        continue;
                    }
                    if (is_numeric($subscriber)) {
                        //this is a local, old style subscription
                        $users[] = new \Minds\Entities\User($guid);
                        continue;
                    }

                    $users[] = new \Minds\Entities\User(json_decode($subscriber, true));
                }

                $users = array_values(array_filter($users, function ($user) {
                    return ($user->enabled != 'no' && $user->banned != 'yes');
                }));
                
                $response['users'] = factory::exportable($users);
                $response['load-next'] = (string) end($users)->guid;
                $response['load-previous'] = (string) key($users)->guid;
                break;
            case 'subscribers':

                if ($pages[1] == "100000000000000519") {
                    break;
                }

                $db = new \Minds\Core\Data\Call('friendsof');
                $subscribers= $db->getRow($pages[1], array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
                if (!$subscribers) {
                    return Factory::response([]);
                }
                $users = array();
                if (get_input('offset') && key($subscribers) != get_input('offset')) {
                    $response['load-previous'] = (string) get_input('offset');
                } else {
                    foreach ($subscribers as $guid => $subscriber) {
                        if ($guid == get_input('offset')) {
                            unset($subscribers[$guid]);
                            continue;
                        }
                        if (is_numeric($subscriber)) {
                            //this is a local, old style subscription
                            $users[] = new \Minds\Entities\User($guid);
                            continue;
                        }

                        //var_dump(print_r($users,true));die();
                        $users[] = new \Minds\Entities\User(json_decode($subscriber, true));
                    }

                    $users = array_values(array_filter($users, function ($user) {
                        return ($user->enabled != 'no' && $user->banned != 'yes');
                    }));

                    $response['users'] = factory::exportable($users);
                    $response['load-next'] = (string) end($users)->guid;
                    $response['load-previous'] = (string) key($users)->guid;
                }
                break;
        }

        return Factory::response($response);
    }

    /**
     * Subscribes a user to another
     * @param array $pages
     *
     * API:: /v1/subscriptions/:guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        if ($pages[0] === 'batch') {
            $guids = $_POST['guids'];

            //temp: captcha tests
            if (Core\Session::getLoggedInUser()->captcha_failed) {
                return Factory::response(['status' => 'error']);
            }

            Queue\Client::build()
              ->setQueue('SubscriptionDispatcher')
              ->send([
                  'currentUser' => Core\Session::getLoggedInUser()->guid,
                  'guids' => $guids
              ]);

            return Factory::response(['status' => 'success']);
        }

        $canSubscribe = Security\ACL::_()->interact(Core\Session::getLoggedinUser(), $pages[0]) &&
            Security\ACL::_()->interact($pages[0], Core\Session::getLoggedinUser(), 'subscribe');

        if (!$canSubscribe) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        $success = elgg_get_logged_in_user_entity()->subscribe($pages[0]);
        $response = array('status'=>'success');
        if (!$success) {
            $response = array(
                'status' => 'error'
            );
        }

        $event = new Core\Analytics\Metrics\Event();
        $event->setType('action')
            ->setAction('subscribe')
            ->setProduct('platform')
            ->setUserGuid((string) Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $pages[0])
            ->push();

        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();
        $success = elgg_get_logged_in_user_entity()->unSubscribe($pages[0]);

        $event = new Core\Analytics\Metrics\Event();
        $event->setType('action')
            ->setAction('unsubscribe')
            ->setProduct('platform')
            ->setUserGuid((string) Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $pages[0])
            ->push();

        $response = array('status'=>'success');
        if (!$success) {
            $response = array(
                'status' => 'error'
            );
        }

        return Factory::response($response);
    }
}
