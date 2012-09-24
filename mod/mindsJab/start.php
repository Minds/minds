<?php

function mindsJab_init(){
			
		elgg_register_action('beechat/get_statuses', elgg_get_plugins_path() . 'mindsJab/actions/get_statuses.php');
		elgg_register_action('beechat/get_icons', elgg_get_plugins_path() . 'mindsJab/actions/get_icons.php');
		elgg_register_action('beechat/get_details',elgg_get_plugins_path() . 'mindsJab/actions/get_details.php');
		elgg_register_action('beechat/get_connection',  elgg_get_plugins_path() . 'mindsJab/actions/get_connection.php');
		elgg_register_action('beechat/get_state',  elgg_get_plugins_path() . 'mindsJab/actions/get_state.php');
		elgg_register_action('beechat/save_state',  elgg_get_plugins_path() . 'mindsJab/actions/save_state.php');

		//register_plugin_hook('action', 'friends/add', 'beechat_xmpp_add_friend');
		//register_plugin_hook('action', 'friends/remove', 'beechat_xmpp_remove_friend');

	
		elgg_extend_view('js/elgg', 'js/json2.js');
		elgg_extend_view('js/elgg', 'js/jquery.cookie.min.js');
		elgg_extend_view('js/elgg', 'js/jquery.scrollTo-min.js');
		elgg_extend_view('js/elgg', 'js/jquery.serialScroll-min.js');
		elgg_extend_view('js/elgg', 'js/b64.js');
		elgg_extend_view('js/elgg', 'js/sha1.js');
		elgg_extend_view('js/elgg', 'js/md5.js');
		elgg_extend_view('js/elgg', 'js/strophe.min.js');
		elgg_extend_view('js/elgg', 'js/jquery.tools.min.js');
		elgg_extend_view('css/elgg', 'beechat/screen.css');
		elgg_extend_view('js/elgg', 'beechat/beechat.js');
		elgg_extend_view('metatags', 'beechat/beechat.userjs');
		
		elgg_extend_view('page/elements/foot', 'beechat/beechat');

	}
/*
function mindJab_pagesetup(){
		global $CONFIG;
		if (elgg_get_context() == 'settings' && isloggedin()) {
			if (get_loggedin_user()->chatenabled) {
				add_submenu_item(elgg_echo('beechat:disablechat'), $CONFIG->wwwroot . "mod/beechat/disablechat.php");
			}
			else
				add_submenu_item(elgg_echo('beechat:enablechat'), $CONFIG->wwwroot . "mod/beechat/enablechat.php");
		}
	}*/

function beechat_xmpp_add_friend($hook, $entity_type, $returnvalue, $params){
		GLOBAL $SESSION;
		GLOBAL $CONFIG;
		
		$jabber_domain = $CONFIG->chatsettings['domain'];
		$dbname = $CONFIG->chatsettings['dbname'];
		$dbhost = $CONFIG->chatsettings['dbhost'];
		$dsn_ejabberd = "mysql:dbname={$dbname};host={$dbhost}";
		
		$user = $CONFIG->chatsettings['dbuser'];
		$password = $CONFIG->chatsettings['dbpassword'];
		
		$friend_guid = get_input('friend', 0);
		if (!$friend_guid || !$friend = get_entity($friend_guid))
			return (false);
		
		try
		{
			$dbh_ejabberd = new PDO($dsn_ejabberd, $user, $password);
			$dbh_ejabberd->beginTransaction();
			
			$sql = 'INSERT INTO rosterusers (username, jid, nick, subscription, ask, server, type) VALUES (?, ?, ?, ?, ?, ?, ?);';
			$sth_ejabberd = $dbh_ejabberd->prepare($sql);
			
			$username = $SESSION->offsetGet('user')->username;
			$jid = $friend->username . '@' . $jabber_domain;
			$nick = $friend->name;
			$subscription = 'B';
			$ask = 'N';
			$server = 'N';
			$type = 'item';
			
			$sth_ejabberd->execute(array($username, $jid, $nick, $subscription, $ask, $server, $type));
			
			$sql = 'INSERT INTO rosterusers (username, jid, nick, subscription, ask, server, type) VALUES (?, ?, ?, ?, ?, ?, ?);';
			$sth_ejabberd = $dbh_ejabberd->prepare($sql);
			
			$username = $friend->username;
			$jid = $SESSION->offsetGet('user')->username . '@' . $jabber_domain;
			$nick = $SESSION->offsetGet('user')->name;
			
			$sth_ejabberd->execute(array($username, $jid, $nick, $subscription, $ask, $server, $type));
			
			$dbh_ejabberd->commit();
			$dbh_ejabberd = null;
		} 
		catch (PDOException $e)
		{
			error_log('beechat_xmpp_add_friend: ' . $e->getMessage());
			$dbh_ejabberd->rollBack();
			return (false);
		}
		
		return $return_value;
	}

function beechat_xmpp_remove_friend($hook, $entity_type, $returnvalue, $params)
{
  	GLOBAL $SESSION;
	GLOBAL $CONFIG;
		
		$jabber_domain = $CONFIG->chatsettings['domain'];
		$dbname = $CONFIG->chatsettings['dbname'];
		$dbhost = $CONFIG->chatsettings['dbhost'];
		$dsn_ejabberd = "mysql:dbname={$dbname};host={$dbhost}";
		
		$user = $CONFIG->chatsettings['dbuser'];
		$password = $CONFIG->chatsettings['dbpassword'];
	
	if (!$friend = get_entity(get_input('friend', 0)))
		return (false);

	try {
		$dbh_ejabberd = new PDO($dsn_ejabberd, $user, $password);
		$dbh_ejabberd->beginTransaction();
		
		$sql = 'DELETE FROM rosterusers WHERE username = ? AND jid = ?;';
		$sth_ejabberd = $dbh_ejabberd->prepare($sql);
		
		$username = $SESSION->offsetGet('user')->username;
		$jid = $friend->username . '@' . $jabber_domain;
		
		$sth_ejabberd->execute(array($username, $jid));
		
		$sql = 'DELETE FROM rosterusers WHERE username = ? AND jid = ?;';
		$sth_ejabberd = $dbh_ejabberd->prepare($sql);
		
		$username = $friend->username;
		$jid = $SESSION->offsetGet('user')->username . '@' . $jabber_domain;
		
		$sth_ejabberd->execute(array($username, $jid));
		
		$dbh_ejabberd->commit();
		$dbh_ejabberd = null;	
	} 
	catch (PDOException $e)
	{
		error_log('beechat_xmpp_remove_friend: ' . $e->getMessage());
		$dbh_ejabberd->rollBack();
		return (false);
	}
	
	return $return_value;
}

register_elgg_event_handler('init', 'system', 'mindsJab_init');
?>
