<?php
/**
 * Elgg westorElggMan plugin
 *
 * @package westorElggMan
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Torsten Wesolek
 * @copyright Torsten Wesolek 2010
 * @link http://community-software-24.org/
 */

	$messageSendOption = $vars['entity']->messageSendOption;
	$copyOutboxOption = $vars['entity']->copyOutboxOption ? $vars['entity']->copyOutboxOption : 'yes';
	$useCronOption = $vars['entity']->useCronOption ? $vars['entity']->useCronOption : 'no';
	$adminOnlyOption = $vars['entity']->adminOnlyOption ? $vars['entity']->adminOnlyOption : 'yes';
	$friendsToRiverOption = $vars['entity']->friendsToRiverOption ? $vars['entity']->friendsToRiverOption : 'no';
	$adminOnlyOption = $vars['entity']->adminOnlyOption ? $vars['entity']->adminOnlyOption : 'no';
	$allowSendToAllOption = $vars['entity']->allowSendToAllOption ? $vars['entity']->allowSendToAllOption : 'no';


	$radio_westorElggManMessages = elgg_view('input/radio', array('internalname' => "params[messageSendOption]", 'name' => "params[messageSendOption]", 'options' => array(elgg_echo('ElggMan_:FullMail') => 'FullMail',elgg_echo('ElggMan_:NotifyOnly') => 'Notify', elgg_echo('ElggMan_:NoMessage') => 'NoMessage', elgg_echo('ElggMan_:NoInbox') => 'NoInbox'), 'value' => $messageSendOption ? $messageSendOption : 'FullMail'));

	echo elgg_echo('ElggMan_:Info') . '<br />';
	echo $radio_westorElggManMessages . '<br />';

// copy to outbox
	echo '<br />';
	echo elgg_echo('ElggMan_:CopyOutboxOption') . '<br />';
	echo '<select name="params[copyOutboxOption]">
	<option value="yes" ' . ($copyOutboxOption == 'yes' ? ' selected' : '') . '>' . elgg_echo('option:yes') . '</option>
	<option value="no" ' . ($copyOutboxOption == 'no' ? ' selected' : '') . '>' . elgg_echo('option:no') . '</option>
	</select>';

// use cron
	echo '<br />';
	echo '<br />';


	echo elgg_echo('ElggMan_:UseCronOption') . '<br />';
	echo '<select name="params[useCronOption]">
	<option value="yes" ' . ($useCronOption == 'yes' ? ' selected' : '') . '>' . elgg_echo('option:yes') . '</option>
	<option value="no" ' . ($useCronOption == 'no' ? ' selected' : '') . '>' . elgg_echo('option:no') . '</option>
	</select>';


// users can send messages to all users?
	echo '<br /><br />';
	echo elgg_echo('ElggMan_:AllowSendToAllOption') . '<br />';
	echo '<select name="params[allowSendToAllOption]">
	<option value="yes" ' . ($allowSendToAllOption == 'yes' ? ' selected' : '') . '>' . elgg_echo('option:yes') . '</option>
	<option value="no" ' . ($allowSendToAllOption == 'no' ? ' selected' : '') . '>' . elgg_echo('option:no') . '</option>
	</select>';

// yes restricts plugin usage to admins
	echo '<br /><br />';
	echo elgg_echo('ElggMan_:adminOnlyOption') . '<br />';
	echo '<select name="params[adminOnlyOption]">
	<option value="yes" ' . ($adminOnlyOption == 'yes' ? ' selected' : '') . '>' . elgg_echo('option:yes') . '</option>
	<option value="no" ' . ($adminOnlyOption == 'no' ? ' selected' : '') . '>' . elgg_echo('option:no') . '</option>
	</select>';

	// valid columns
	$columns = array(
		"username" => elgg_echo("ElggMan_:cUserName"),
		"email" => elgg_echo("ElggMan_:cEmail"),
		"mobile" => elgg_echo("ElggMan_:cMobile"),
		"time_created" => elgg_echo("ElggMan_:cSince"),
		"last_login" => elgg_echo("ElggMan_:cLastLogin"),
		"last_action" => elgg_echo("ElggMan_:cLastAction"),
		"location" => elgg_echo("ElggMan_:cLocation")
		);

	// columns initial for admin
	$columnsAdmin = array(
		"username" => elgg_echo("ElggMan_:cUserName"),
		"email" => elgg_echo("ElggMan_:cEmail"),
//		"mobile" => elgg_echo("ElggMan_:cMobile"),
		"time_created" => elgg_echo("ElggMan_:cSince"),
		"last_login" => elgg_echo("ElggMan_:cLastLogin"),
//		"last_action" => elgg_echo("ElggMan_:cLastAction"),
//		"location" => elgg_echo("ElggMan_:cLocation")
		);

	// columns initial for user
	$columnsUser = array(
//		"username" => elgg_echo("ElggMan_:cUserName"),
//		"email" => elgg_echo("ElggMan_:cEmail"),
//		"mobile" => elgg_echo("ElggMan_:cMobile"),
		"time_created" => elgg_echo("ElggMan_:cSince"),
		"last_login" => elgg_echo("ElggMan_:cLastLogin"),
//		"last_action" => elgg_echo("ElggMan_:cLastAction"),
		"location" => elgg_echo("ElggMan_:cLocation")
		);

	echo '<br /><br />';
	echo elgg_echo('ElggMan_:varColumnsAdmin') . '<br />';
	echo '<table border="0">';
	foreach($columns as $columnname => $columntext){
		$name = "varColumnsAdmin_$columnname";
		$checked_on = $vars['entity']->$name ?
			($vars['entity']->$name == "on" ? " checked" : "") :
			(in_array($columntext, $columnsAdmin) ? " checked" : "");
		$checked_off = ($checked_on == " checked") ? "" : " checked";

		echo '<tr><td>' . $columntext . ': </td><td><input type="radio" name="params[varColumnsAdmin_'.$columnname.']" value="on"' . $checked_on . '> <strong>' . elgg_echo('option:yes') . '</strong> &nbsp; </td><td><input type="radio" name="params[varColumnsAdmin_'.$columnname.']" value="off"' . $checked_off . '> ' . elgg_echo('option:no') . '</td></tr>';
	}
	echo '</table>';

	echo '<br /><br />';
	echo elgg_echo('ElggMan_:varColumnsUser') . '<br />';
	echo '<table border="0">';
	foreach($columns as $columnname => $columntext){
		$name = "varColumnsUser_$columnname";
		$checked_on = $vars['entity']->$name ?
			($vars['entity']->$name == "on" ? " checked" : "") :
			(in_array($columntext, $columnsUser) ? " checked" : "");
		$checked_off = ($checked_on == " checked") ? "" : " checked";

		echo '<tr><td>' . $columntext . ': </td><td><input type="radio" name="params[varColumnsUser_'.$columnname.']" value="on"' . $checked_on . '> <strong>' . elgg_echo('option:yes') . '</strong> &nbsp; </td><td><input type="radio" name="params[varColumnsUser_'.$columnname.']" value="off"' . $checked_off . '> ' . elgg_echo('option:no') . '</td></tr>';
	}
	echo '</table>';

// friendrequests to river
//	echo '<br /><br />';
//	echo elgg_echo('ElggMan_:FriendsToRiverOption') . '<br />';
//	echo '<select name="params[friendsToRiverOption]">
//	<option value="yes" ' . ($friendsToRiverOption == 'yes' ? ' selected' : '') . '>' . elgg_echo('option:yes') . '</option>
//	<option value="no" ' . ($friendsToRiverOption == 'no' ? ' selected' : '') . '>' . elgg_echo('option:no') . '</option>
//	</select>';

	echo '<br /><br />';

?>
