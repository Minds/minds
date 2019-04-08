<?php

namespace Minds\Core\Events\Hooks;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Events\Dispatcher;

class Register
{
    public function init()
    {
        Dispatcher::register('register', 'user', function ($event) {
            $params = $event->getParameters();

            //$guid = $params['user']->guid;
            //subscribe to minds channel
            //$minds = new Entities\User('minds');
            //$params['user']->subscribe($minds->guid);

            //setup chat keys
            /*$openssl = new Core\Messenger\Encryption\OpenSSL();
            $keystore = (new Core\Messenger\Keystore($openssl))
                ->setUser($params['user']);
            $keypair = $openssl->generateKeypair($params['password']);

            $keystore->setPublicKey($keypair['public'])
                ->setPrivateKey($keypair['private'])
                ->save();*/

            //@todo again, maybe in a background task?
            if ($params['referrer']) {
                $user = new Entities\User(strtolower(ltrim($params['referrer'], '@')));
                if ($user->guid) {
                    $params['user']->referrer = (string) $user->guid;
                    $params['user']->save();
                    $params['user']->subscribe($user->guid);
                }
            }
        });

        Dispatcher::register('register/complete', 'user', function ($event) {
            $params = $event->getParameters();
            //temp: if captcha failed
            if ($params['user']->captcha_failed) {
                return false;
            }
            //send welcome email
            try {
                Core\Queue\Client::build()->setQueue('Registered')
                    ->send([
                        'user_guid' => $params['user']->guid,
                    ]);
                //Delay by two hours so the user has time to complete their profile
                Core\Queue\Client::build()->setQueue('WelcomeEmail')
                    ->send([
                        'user_guid' => $params['user']->guid,
                    ], 7200);
            } catch (\Exception $e) {
            }
        });
    }
}
