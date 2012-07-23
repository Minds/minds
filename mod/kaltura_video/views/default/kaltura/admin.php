<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");

//get the page type
$type = $vars['type'];
$configured = $vars['configured'];


$form_body = '';
if($type == 'partner_wizard') {
	//action
	$action = "kaltura_video/wizard";
	$form_body .= elgg_view('kaltura/admin.wizard',array('configured'=>$configured));

	$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));

}
else {
	$action = "kaltura_video/admin";
	if($type == 'server') {
		$form_body .= elgg_view('kaltura/admin.server',array('configured'=>$configured));
		$form_body .= elgg_view('kaltura/admin.partnerid',array('configured'=>$configured));
		$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
	}
	elseif($type == 'custom') {
		$form_body .= elgg_view('kaltura/admin.custom',array('configured'=>$configured));
		$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
	}
	elseif($type == 'behavior') {
		$form_body .= elgg_view('kaltura/admin.behavior',array('configured'=>$configured));
		$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
	}
	elseif($type == 'advanced') {
		$form_body .= elgg_view('kaltura/admin.advanced',array('configured'=>$configured));
	}
	elseif($type == 'credits') {
		$form_body .= elgg_view('kaltura/admin.credits',array('configured'=>$configured));
	}

	//hope of donations...
	$form_body .= '<hr /><p>' . elgg_echo('kalturavideo:note:donate') .' <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=3518572" onclick="window.open(this.href);return false;"><img src="http://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate" style="vertical-align:middle;" /></a></p>';

}

//additional vars
$form_body .= elgg_view('input/hidden', array('internalname' => 'type', 'value' => $type));

//display the form
echo elgg_view('input/form', array('action' => "{$vars['url']}action/$action", 'body' => $form_body));

?>
