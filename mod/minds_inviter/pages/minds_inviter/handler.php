<?php
	session_start();
	
	require('configuration.php');
	
	function __autoload($name){
		if(file_exists(dirname(__FILE__)."/class/".$name.".class.php")){
			require(dirname(__FILE__)."/class/".$name.".class.php");
		} else {
			throw new Exception("Class ".$name." not found");
		}
	}
	
	if(!isset($_REQUEST['provider'])){
		echo ("Provider argument was not set");
		exit;
	}
	
	if(!class_exists($_REQUEST['provider']."Oauth",true)){
		echo ("The provider class could not be found");
		exit;
	}
	
	$classname = $_REQUEST['provider']."Oauth";
	
	try {
		$oauth_obj = new $classname();
	} catch (Exception $E){
		echo $E->getMessage();
		exit;
	}
	
	if(isset($_REQUEST['oauth_token'])){
		$oauth_token = $_REQUEST['oauth_token'];
	}
	
	if(isset($_REQUEST['wrap_verification_code'])){
		$oauth_token = $_REQUEST['wrap_verification_code'];
	}
	
	if(isset($_REQUEST['default_message'])){
		$_SESSION['oauth']['default_message'] = $_REQUEST['default_message'];
	}
	
	if(isset($_REQUEST['emails'])){
		
		$mailer = new mailer($_REQUEST['emails']);
		
		if(isset($_REQUEST['message'])){
			$mailer->setMessage($_REQUEST['message']);
		}
		
		$mailer->send();
		
		?>
			<script>window.close();</script>
		<?
		
		
	} else if(!isset($oauth_token)){
		header("location:".$oauth_obj->getLoginUrl());
	} else {
		if($oauth_obj->handleCallback($oauth_token)){
			$inviteForm = new inviteForm($oauth_obj->getContacts());
			echo $inviteForm->display();
		} else {
			//header("location:".$oauth_obj->getLoginUrl());
			echo "something went wrong";
		}
	}

?>
