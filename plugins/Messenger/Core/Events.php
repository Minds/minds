<?php
/**
 * Messenger event handlers
 */
namespace Minds\Plugin\Messenger\Core;

use Minds\Core;
use Minds\Core\Sockets;
use Minds\Api;
use Minds\Entities\User;
use Minds\Plugin\Messenger;

class Events
{
    public function setup()
    {
        /**
        * if it's a mutual match then create a conversation
        */
        Core\Events\Dispatcher::register('subscribe', 'all', function($event) {
            $params = $event->getParameters();

            $isMutual = (new Messenger\Helpers\Subscriptions)->isMutual($params['user_guid'], $params['to_guid']);

            if ($isMutual) {
                $conversation = new Messenger\Entities\Conversation();
                $conversation
                    ->setParticipant($params['user_guid'])
                    ->setParticipant($params['to_guid'])
                    ->saveToLists();
            }
        });

        /**
         * Extend User->export()
         */
        Core\Events\Dispatcher::register('export:extender', 'all', function($event) {
            $params = $event->getParameters();

            if ($params['entity'] instanceof User){
                $keystore = (new Messenger\Core\Keystore())
                    ->setUser($params['entity']);

                if ($keystore->getPrivateKey()){
                    $export = [ 'chat' => true ];
                }

                $event->setResponse($export);
            }
        });

        /**
         * Extend Entity mapper
         */
        Core\Events\Dispatcher::register('entities:map', 'all', function($event) {
            $params = $event->getParameters();

            if ($params['row']->subtype == 'message') {
                $e->setResponse(new Entities\Message($params['row']));
            } elseif ($params['row']->subtype == 'call_missed') {
                $e->setResponse(new Entities\CallMissed($params['row']));
            } elseif ($params['row']->subtype == 'call_ended') {
                $e->setResponse(new Entities\CallEnded($params['row']));
            }
        });

        /**
         * Extend ACL for Messages entity checks
         */
        Core\Events\Dispatcher::register('acl:read', 'all', function($event) {
            $params = $event->getParameters();
            $message = $params['entity'];
            $user = $params['user'];

            if($message instanceof Entities\Message){
                $key = "message:$user->guid";
                if ($message->$key) {
                    $event->setResponse(true);
                }
            }
        });

        Core\Events\Dispatcher::register('acl:block', 'all', function($event) {
            $params = $event->getParameters();
            $from = $params['from'];
            $user = $params['user'];

            if (!$from || !$user) {
                return;
            }

            $isMutual = (new Messenger\Helpers\Subscriptions)->isMutual($from, $user);

            try {
                (new Sockets\Events())
                  ->to("messenger:{$from}")
                  ->emit('block', (string) $user);
            } catch (\Exception $e) { /* TODO: To log or not to log */ }
        });

        Core\Events\Dispatcher::register('acl:unblock', 'all', function($event) {
            $params = $event->getParameters();
            $from = $params['from'];
            $user = $params['user'];

            if (!$from || !$user) {
                return;
            }

            $isMutual = (new Messenger\Helpers\Subscriptions)->isMutual($from, $user);

            try {
                (new Sockets\Events())
                  ->to("messenger:{$from}")
                  ->emit('unblock', (string) $user);
            } catch (\Exception $e) { /* TODO: To log or not to log */ }
        });
    }
}
