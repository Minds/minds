<?php
/**
 * Elgg notifications plugin
 *
 * @package ElggNotifications
 */

namespace minds\plugin\notifications;

use Minds\Core;
use minds\Api;

class start extends \ElggPlugin{

	/**
	 * Initialise the plugin
	 */
	public function init(){
		\elgg_register_plugin_hook_handler('cron', 'minute', array($this, 'cronHandler'));
		\elgg_register_plugin_hook_handler('cron', 'daily', array($this, 'cronHandler'));
		\elgg_register_plugin_hook_handler('cron', 'weekly', array($this, 'cronHandler'));
		\add_subtype('notificaiton', 'email', 'ElggNotificationEmail');

		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			//var_dump($row);
			if($row->type == 'notification')
				return new entities\notification($row);
		});

    Api\Routes::add('v1/notifications', "minds\\plugin\\notifications\\api\\v1\\notifications");
    Api\Routes::add('v1/invite', "minds\\plugin\\notifications\\api\\v1\\invite");

		$link = new Core\Navigation\Item();
		Core\Navigation\Manager::add($link
			->setPriority(6)
			->setIcon('notifications')
			->setName('Notifications')
			->setTitle('Notificaitons')
			->setPath('/Notifications')
			->setExtras(array(
				'counter' => (string) Core\Session::isLoggedIn() ? self::getCount() : 0
			)),
			"topbar"
		);

		\elgg_register_plugin_hook_handler('notification', 'all', array($this,'createNotification'));

		\elgg_register_event_handler('create', 'all', array($this, 'createHook'));

	}

	/**
	 * Return a count of notifications
	 * @return int
	 */
	static public function getCount($cache =true){
		if($cache){
			$user = \elgg_get_logged_in_user_entity();
			//return $user->notifications_count;
		}

		$lu = new core\Data\lookup();
		$result = $lu->get("notifications:count", array('offset'=>elgg_get_logged_in_user_guid()));
		if(!isset($result[elgg_get_logged_in_user_guid()])){
			return (int) 0;
		}
		return (int) $result[elgg_get_logged_in_user_guid()];

	}

	/**
	 * Increase a users notification counter
	 */
    public function increaseCounter($user_guid){
        return false;
	 	try{
		 	elgg_set_ignore_access(true);
			$user = new \ElggUser($user_guid);
			if($user){
				$user->notifications_count++;
				$user->save();
			}
			elgg_set_ignore_access(false);

			$lu = new core\Data\lookup();
			$lu->set("notifications:count", array($user_guid => 1));
		}catch(Exception $e){
            var_dump($e);
            return false;
		}
	 }

	 /**
	  * Reset user notification counter
	  */
	 public function resetCounter($user_guid = NULL){
	 	try{
		 	if(!$user_guid)
				$user_guid = elgg_get_logged_in_user_guid();

			elgg_set_ignore_access(true);
			$user = new \ElggUser($user_guid);
			$user->notifications_count = 0;
			$user->save();
			elgg_set_ignore_access(false);

			$lu = new core\Data\lookup();
			$lu->set("notifications:count", array($user_guid => 0));
		}catch(Exception $e){}
	 }

	/**
	 * Return a list of notifications
	 * @return array
	 */
	public function getNotifications($user = NULL, $limit = 12, $offset = ""){
		if(!$user)
			$user = \elgg_logged_in_user_entity();

		return elgg_get_entities(array('attrs'=>array('namespace'=>'notifications:'.$user->guid), 'limit'=>$limit,'offset'=>$offset ));
	}


	/**
	 * Create a new notification
	 *
	 */
	public function createNotification($hook, $type, $return, $params = array()){
		$defaults = array(
			'to' => array(),
			'from' => \elgg_get_logged_in_user_guid(),
			'object_guid'=> NULL
		);
		$params = array_merge($defaults, $params);
        foreach($params['to'] as $t){
		//	if($t != $params['from']){
				$notification = new entities\notification();
				$notification->to_guid = (int)$t;
				$notification->object_guid = $params['object_guid'];
				$notification->from_guid = $params['from'];
				$notification->notification_view = $params['notification_view'];
				$notification->description = $params['description'];
				$notification->read = 0;
				$notification->access_id = 2;
				$notification->owner_guid = \elgg_get_logged_in_user_guid();
				$notification->params = json_encode($params['params']);
				$notification->time_created = time();
				$notification->save();
		//	}
            $message = "";

            $params['title'] = htmlspecialchars_decode( $params['title']);
            $params['description'] = htmlspecialchars_decode( $params['description'] );
            switch($params['notification_view']){
                case "friends":
		    $message = \Minds\Core\Session::getLoggedinUser()->name . " subscribed to you";
		    break;
	        case "comment":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " commented: " . $params['description'];
                    break;
                case "like":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " voted up " . $params['title'];
                    break;
                case "tag":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " mentioned you in a post: " . $params['description'];
                    break;
                case "remind":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " reminded " . $params['title'];
                    break;
                case "boost_gift":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " gifted you " . $params['impressions'] . " view";
                    break;
                case "boost_request":
                    $message = \Minds\Core\Session::getLoggedinUser()->name . " has requested a boost for " . $params['points'] . " points";
                    break;
                case "boost_accepted":
                    $message = $params['impressions'] . " views for " . $params['title'] . ' were accepted';
                    break;
                case "boost_rejected":
                    $message = "Your boost request for " . $params['title'] . " were rejected";
                    break;
                case "boost_completed":
                    $message =  $params['impressions'] . "/" . $params['impressions'] . " impressions were met for " . $params['title'];
                    break;
                default:
                    $message = "You have a notification";
            }

            Core\Queue\Client::build()->setExchange("mindsqueue")
                                      ->setQueue("Push")
                                      ->send(array(
                                            "user_guid"=>$t,
                                            "message"=>$message,
                                            "uri" => 'notification'
                                           ));
            //\Minds\plugin\notifications\Push::queue($t, array('message'=>$message, 'uri'=>'notification'));
		}
		return $return;
	}

	/**
	 * Notifications cron handler
	 * @return void
	 */
	public function cronHandler($hook, $type, $params, $return){
		/**
		 * FOR SECURITY ONLY ALLOW THIS TO BE CALLED FROM LOCALHOST!
		 */
		if($_SERVER['HTTP_HOST'] != 'localhost'){
			return false;
		}

		$queue = \elgg_get_entities(array('type'=>'notification', 'subtype'=>'email', 'limit'=>0));

		foreach($queue as $q){
			if($q->send()){
				echo 'sent';
			} else {
				if($q->state == 'completed' && $q->time_created <= time()-3600){
					$q->delete();
				}
				echo $q->state;
			}
		}

		$mail = new \ElggNotificationEmail();
		switch($type){
			case 'daily':
				$mail->subject = 'Your Minds Headlines';
				$mail->subscription = $type;
				$mail->send();
				break;
			case 'weekly':
				$mail->subject = 'Your Minds Headlines';
				$mail->subscription = $type;
				$mail->send();
				break;
		}
	}

	/**
	 * Create hook
	 * @return void
	 */
    public function createHook($hook, $type, $params, $return = NULL){
		if($type == 'activity' || $type == 'comment'){
			if($params->message)
				$message = $params->message;
			if($type == 'comment')
				$message = $params->description;
		    if($params->title)
                $message .= $params->title;
			if (preg_match_all('!@(.+)(?:\s|$)!U', $message, $matches)){
				$usernames = $matches[1];
				$to = array();
				foreach($usernames as $username){
					$user= new \minds\entities\user(strtolower($username));
					if($user->guid)
						$to[] = $user->guid;
				}
				if($to)
					\elgg_trigger_plugin_hook('notification', 'activity', array(
						'to'=>$to,
						'object_guid' => $params->guid,
						'notification_view' => 'tag',
						'description' => $params->message,
                        'title' => $params->title
						));
			}
		}
	}
}

/**
 * Create a notification
 *
 * @param $to int/arr - guid(s) of the user to recieve the notification
 * @param $from int - guid of the user making the notification
 * @param $object object - the entity or object in question
 * @param $params array - any further information, such as a comment
 *
 * @return bool - success or failed
 */
function notification_create($to, $from, $object, $params){
	return elgg_trigger_plugin_hook('notification', 'all', array('to'=>$to, 'from'=>$from, 'object_guid'=>$object, 'params'=>$params));

	//if the user and from are not the same then send!
	//if($to != $from){
	foreach($to as $t){
		if($t != $from){
			$notification = new ElggNotification();
			$notification->to_guid = $t;
			$notification->object_guid = $object;
			$notification->from_guid = $from;
			$notification->notification_view = $params['notification_view'];
			$notification->description = $params['description'];
			$notification->read = 0;
			$notification->access_id = 2;
			$notification->params = serialize($params);
			$notification->time_created = time();

			$notification->save();
		}
	}
	//}

	return true;

}
