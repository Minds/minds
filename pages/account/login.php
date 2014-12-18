<?php
/**
 * Assembles and outputs a login page.
 *
 * This page serves as a fallback for non-JS users who click on the login
 * drop down link.
 *
 * If the user is logged in, this page will forward to the front page.
 *
 * @package Elgg.Core
 * @subpackage Accounts
 */

if (elgg_is_logged_in()) {
	forward('');
}

$login_box = elgg_view('core/account/login_box');

$sep = "<div style=\"margin: auto; width: 300px; text-align: center; padding: 16px; font-weight: bold;\"> - or create a new account - </div>";

$form_params = array(
	'action' => 'action/register',
	'class' => 'elgg-form-account',
);

$body_params = array(
	'friend_guid' => $friend_guid,
	'invitecode' => $invitecode
);
$register_box = elgg_view_form('register', $form_params, $body_params);

$content = elgg_view_layout('one_column', array('content' => $login_box . $sep . $register_box));
echo elgg_view_page(elgg_echo('login'), $content);
