<?php
/**
 * Gatherings page handler
 */
namespace minds\plugin\gatherings\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;
use minds\plugin\gatherings\counter;

class conversation extends core\page implements interfaces\page{
	
	public $context = 'gatherings';
	private $passphrase = NULL;
	
	/**
	 * Reading messages and getting lists of messages
	 */
	public function get($pages){
		if(!isset($pages[0]) && get_input('u') || $pages[0] == 'new')
			$pages[0] = get_input('u');

		counter::clear();

		// a list of open conversations
		$conversations = \minds\plugin\gatherings\start::getConversationsList();
		
		$show = true;
		$option = \elgg_get_plugin_user_setting('option', elgg_get_logged_in_user_guid(), 'gatherings');
		
		if((int)$option == 1 && !$this->passphrase && (!isset($_SESSION['tmp_privatekey']) || !isset($_COOKIE['tmp_priv_pswd']))){
			//we need a password from the user...
			$content = elgg_view_form('message_unlock', array('action'=>elgg_get_site_url() . 'gatherings/conversation/'.$pages[0].'/unlock'));
			
			$show = false;
			
			if(isset($_SESSION['tmp_privatekey'])){
				unset($_SESSION['tmp_privatekey']);
				unset($_SESSION['tmp_privatekey_ts']);
			}
		}
		
		if($show){
			$users = array();
			if(strpos($pages[0], ':') !== FALSE){
				
				//this is a group chat
				$usernames = explode(':', $pages[0]);
				foreach($usernames as $u){
					$u = strtolower($u);
					$users[] = new \minds\entities\user($u);
				}
				
			} else {
				$user = new \minds\entities\user(strtolower($pages[0]));
				if(!in_array($user, $conversations)){
					$user->last_msg = time();
					$user->unread = 0;
					$conversations[] = $user;
				}
			}
			
			
			$conversation = new entities\conversation(elgg_get_logged_in_user_guid());
			if($users){
				foreach($users as $user){
					if($user->guid)
						array_push($conversation->participants, $user->guid);
				}
				
			} else {
				$conversation = new entities\conversation(elgg_get_logged_in_user_guid(), $user->guid);
			}
			
			$ik = $conversation->getIndexKeys();
			$guids = core\Data\indexes::fetch("object:gathering:conversation:".$ik[0], array('limit'=>12, 'offset'=>get_input('offset')));

			if($guids){
			
				foreach($conversations as $c){
					if(in_array($c->guid, $conversation->participants)){
						if($c->unread)
							$conversation->clearCount();
					}
				}
	
				$messages = core\entities::get(array('guids'=>$guids));
				foreach($messages as $k => $message){
					$messages[$k] = new entities\message($message, $this->passphrase);
					//var_dump($message->decryptMessage());
				}
				$messages = array_reverse($messages);
				$content = elgg_view('gatherings/conversation', array('conversation'=>$conversation, 'messages'=>$messages));
			} else {
				$content = elgg_view('gatherings/conversation', array('conversation'=>$conversation, 'messages'=>array()));
			}
			$content .= elgg_view_form('conversation', array('action'=>elgg_get_site_url() . 'gatherings/conversation/'.$user->guid), array('encrypted'=>$encrypted,'user'=>$user, 'conversation'=>$conversation));
		}

		$layout = elgg_view_layout('one_sidebar_alt', array('content'=>$content, 'sidebar'=>elgg_view('gatherings/conversations/list', array('conversations'=>$conversations, 'conversation'=>$conversation))));
		echo $this->render(array('body'=>$layout, 'class'=>'white-bg'));
		
	}
	
	/**
	 * Posting messages 
	 */
	public function post($pages){
		
		if(isset($pages[1]) && $pages[1] == 'unlock'){
			$this->passphrase = get_input('passphrase');
			
			$new_pswd = base64_encode(openssl_random_pseudo_bytes(128));
			$tmp = helpers\openssl::temporaryPrivateKey(\elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings'), $this->passphrase, $new_pswd);
			$_SESSION['tmp_privatekey'] = $tmp;
			$_SESSION['tmp_privatekey_ts'] = time();
			
			setcookie('tmp_priv_pswd', $new_pswd, time() + (60 * 60 * 60 * 24), '/', NULL, NULL, true);
			
			$this->passphrase = $new_pswd;
			return $this->forward(REFERRER);
		}

		if(!get_input('message')){
			header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			exit;
		}
		
		$conversation = new entities\conversation();
		$conversation->participants = $_POST['participants'];
		
		$message = new entities\message($conversation);
		$message->setMessage(get_input('message'))
				->save();

		$conversation->update();	
	
		if(elgg_is_xhr()){
			echo elgg_view_entity($message);
		}
			
		$this->forward(REFERRER);
	}
	
	/**
	 * Uploading content via messages (coming soon)
	 */
	public function put($pages){}
	
	/**
	 * Deleting messages
	 */
	public function delete($pages){
		
		$message = new entities\message($pages[0]);
		$message->delete();
		
	}
	
}
