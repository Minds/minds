<?php
/**
 * Minds Messenger
 *
 * @package Minds
 * @subpackage Plugin/Messenger
 * @author Mark Harding (mark@minds.com)
 *
 */

namespace Minds\Plugin\Messenger;

use Minds\Components;
use Minds\Core;
use Minds\Api;

class start extends Components\Plugin{

	public function init(){

      Api\Routes::add('v1/gatherings', '\\Minds\\Plugin\\Messenger\\Controllers\\api\\v1\\conversations');
      Api\Routes::add('v1/conversations', '\\Minds\\Plugin\\Messenger\\Controllers\\api\\v1\\conversations');
      Api\Routes::add('v1/keys', '\\Minds\\Plugin\\Messenger\\Controllers\\api\\v1\\keys');

      /**
       * if it's a mutual match then create a conversation
       */
      Core\Events\Dispatcher::register('subscribe', 'all', function($event){
          $params = $event->getParameters();

          $friendsof = new Core\Data\Call('friendsof');
          $mutual = false;
          if($item = $friendsof->getRow($params['user_guid'], array('offset'=>$params['to_guid'], 'limit'=>1))){
                  if($item && key($item) == $params['to_guid'])
                      $mutual = true;
          }

          if($mutual){
              $conversation = new entities\conversation($params['user_guid'], $params['to_guid']);
              $conversation->update(0, true);
          }
      });

			$link = new Core\Navigation\Item();
			Core\Navigation\Manager::add($link
				->setPriority(5)
				->setIcon('chat_bubble')
				->setName('Messenger')
				->setTitle('Messenger')
				->setPath('/Messenger')
				//->setVisibility(0) //only show for loggedin
			);

			Core\Events\Dispatcher::register('entities:loader', 'all', function($event){
				$params = $event->getParameters();
				if($params['row']->subtype == 'message')
					return new Entities\Message($params['row']);
				if($params['row']->subtype == 'call_missed')
					return new Entities\CallMissed($params['row']);
				if($params['row']->subtype == 'call_ended')
					return new Entities\CallEnded($params['row']);
			});

			Core\Events\Dispatcher::register('acl:read', 'all', function($event){
				$params = $event->getParameters();
				$message = $params['entity'];
				$user = $params['user'];

				if($message instanceof Entities\Message){
					$key = "message:$user->guid";
					if($message->$key)
						$event->setResponse(true);
				}

			});

	}

	static public function getConversationsList($offset= ""){
        //@todo review for scalability. currently for pagination we need to load all conversation guids/time
        $conversation_guids = Core\Data\indexes::fetch("object:gathering:conversations:".elgg_get_logged_in_user_guid(), array('limit'=>10000));
				if($conversation_guids){
						$conversations = [];

            arsort($conversation_guids);
            $i = 0;
            $ready = false;
            foreach($conversation_guids as $user_guid => $data){
                if(!$ready && $offset){
                    if($user_guid == $offset)
                        $ready = true;
                    continue;
                }
                if($i++ > 12 && !$offset)
                    continue;

                if($i++ > 24){
                    continue;
                }

                if($user_guid == $offset){
                    unset($conversation_guids[$user_guid]);
                    continue;
                }
            	if(is_numeric($data)){
					$ts = $data;
					$unread = 0;
				} else {
					$data = json_decode($data, true);
					$unread = $data['unread'];
					$ts = $data['ts'];
				}
				$u = new \Minds\Entities\User($user_guid);
				$u->last_msg = $ts;
				$u->unread = $unread;
				if($u->username && $u->guid != core\Session::getLoggedinUser()->guid){
					$conversations[] = $u;
				}
				continue;
			}

		}
		return $conversations;
	}

}
