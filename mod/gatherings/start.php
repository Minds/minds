<?php
/**
 * Minds gatherings.
 *
 * @package Minds
 * @subpackage gatherings
 * @author Mark Harding (mark@minds.com)
 *
 */

namespace minds\plugin\gatherings;

use Minds\Components;
use Minds\Core;
use Minds\Api;

class start extends Components\Plugin{

	public function init(){

      Api\Routes::add('v1/gatherings', '\\minds\\plugin\\gatherings\\api\\v1\\conversations');
      Api\Routes::add('v1/conversations', '\\minds\\plugin\\gatherings\\api\\v1\\conversations');
      Api\Routes::add('v1/keys', '\\minds\\plugin\\gatherings\\api\\v1\\keys');

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

			//@todo move to new oop style
			\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
				//var_dump($row);
				if($row->subtype == 'message')
					return new entities\message($row);
			});

			//@todo move to new oop style
			\elgg_register_plugin_hook_handler('acl', 'all', array($this, 'acl'));

	}

	/**
	 * Encryptor
	 */
	public function encrypt($message){
		$user = \elgg_get_logged_in_user_entity();

	}

	/**
	 * Sets the sidebar menus
	 */
	public function pageSetup(){
		if(elgg_get_context() == 'gatherings'){

		}
	}

	static public function getConversationsList($offset= ""){
        //@todo review for scalability. currently for pagination we need to load all conversation guids/time
        $conversation_guids = core\Data\indexes::fetch("object:gathering:conversations:".elgg_get_logged_in_user_guid(), array('limit'=>10000));
		if($conversation_guids){
			$conversations = array();

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
				$u = new \minds\entities\user($user_guid);
				$u->last_msg = $ts;
				$u->unread = $unread;
				if($u->username && $u->guid != core\session::getLoggedinUser()->guid){
					$conversations[] = $u;
				}
				continue;
			}

		}
		return $conversations;
	}


	/**
	 * Extends the acl to allow access to message users are supposed to see
	 */
	public function acl($event, $type, $return, $params){

		$message = $params['entity'];
		$user = $params['user'];

		if($message instanceof \minds\plugin\gatherings\entities\message){
			$key = "message:$user->guid";
			if($message->$key)
				return true;
		}

		return $return;

	}

}
