<?php
/**
 */

namespace Minds\Core\Events\Hooks;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Core\Events\Dispatcher;

class Register{

    public function init()
    {
        Dispatcher::register('register', 'user', function ($event) {
            $params = $event->getParameters();

            $guid = $params['user']->guid;
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
                  'to' => [ $guid ],
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
        });

        Dispatcher::register('register/complete', 'user', function($event) {
            $params = $event->getParameters();
            //send welcome email
            $template = new Core\Email\Template();
            $template
              ->setTemplate()
              ->setBody('welcome.tpl')
              ->set('guid', $params['user']->guid)
              ->set('username', $params['user']->username)
              ->set('email', $_POST['email'])
              ->set('user', $params['user']);
            $message = new Core\Email\Message();
            $message->setTo($params['user'])
              ->setSubject("Welcome to Minds. Introduce yourself.")
              ->setHtml($template);
            $mailer = new Core\Email\Mailer();
            $mailer->queue($message);
        });
    }

}
