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

class start extends Components\Plugin
{

	public function init()
	{

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

			Core\Events\Dispatcher::register('entities:map', 'all', function($event){
				$params = $event->getParameters();
				if($params['row']->subtype == 'message')
					$e->setResponse(new Entities\Message($params['row']));
				if($params['row']->subtype == 'call_missed')
					$e->setResponse(new Entities\CallMissed($params['row']));
				if($params['row']->subtype == 'call_ended')
					$e->setResponse(new Entities\CallEnded($params['row']));
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

}
