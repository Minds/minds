<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Helpers;

class register implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * NOT AVAILABLE
     */
    public function get($pages)
    {
        return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
    }

    /**
     * Registers a user
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Create a new channel",
     *     path="/v1/register",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['username']) || !isset($_POST['email'])) {
            return Factory::response(['status'=>'error']);
        }

        if (!$_POST['username'] || !$_POST['password'] || !$_POST['username'] || !$_POST['email']) {
            return Factory::response(['status'=>'error', 'message' => "Please fill out all the fields"]);
        }

        try {
            $guid = register_user($_POST['username'], $_POST['password'], $_POST['username'], $_POST['email'], false);
            $params = array(
                'user' => new Entities\User($guid),
                'password' => $_POST['password'],
                'friend_guid' => "",
                'invitecode' => ""
            );
            elgg_trigger_plugin_hook('register', 'user', $params, true);

            //subscribe to minds channel
          	$minds = new Entities\User('minds');
          	$params['user']->subscribe($minds->guid);

            Helpers\Wallet::createTransaction($guid, 100, $guid, "Welcome.");
            Core\Events\Dispatcher::trigger('notification', 'welcome', array(
                'to'=>array($guid),
                'from' => 100000000000000519,
                'notification_view' => 'welcome_points',
                'params' => array('points'=>100),
                'points' => 100
                ));

            //@todo maybe put this in background process
            foreach (array("welcome_boost", "welcome_chat", "welcome_discover") as $notif_type) {
                Core\Events\Dispatcher::trigger('notification', 'welcome', array(
                'to'=>array($guid),
                'from' => "100000000000000519",
                'notification_view' => $notif_type,
                ));
            }

            //@todo again, maybe in a background task?
            if (isset($_POST['referrer']) && $_POST['referrer']) {
                $user = new Entities\User(strtolower(ltrim($_POST['referrer'], '@')));
                if ($user->guid) {
                    Helpers\Wallet::createTransaction($user->guid, 100, $guid, "Referred @" . $_POST['username']);
                }
            }

            //send welcome email
            $template = new Core\Email\Template();
            $template->setBody('welcome.tpl')
              ->set('guid', $params['user']->guid)
              ->set('username', $params['user']->username)
              ->set('user', $params['user']);
            $message = new Core\Email\Message();
            $message->setTo($params['user'])
              ->setSubject("Welcome to Minds. Introduce yourself.")
              ->setHtml($template);
            $mailer = new Core\Email\Mailer();
            $mailer->queue($message);

            login($params['user']);
            $response = array(
              'guid' => $guid,
              'user' => $params['user']->export()
            );
        } catch (\Exception $e) {
            $response = array('status'=>'error', 'message'=>$e->getMessage());
        }
        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
