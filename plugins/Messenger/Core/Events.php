<?php
/**
 * Messenger event handlers
 */
namespace Minds\Plugin\Messenger\Core;

use Minds\Core;
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

            $friendsof = new Core\Data\Call('friendsof');
            $mutual = false;

            if ($item = $friendsof->getRow($params['user_guid'], [ 'offset' => $params['to_guid'], 'limit' => 1 ])) {
                if ($item && key($item) == $params['to_guid']) {
                    $mutual = true;
                }
            }

            if($mutual) {
                $conversation = new Messenger\Entities\Conversation();
                $conversation->setParticipant($params['user_guid'])
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
    }
}
