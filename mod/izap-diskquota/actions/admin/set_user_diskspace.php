<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version {version} $Revision: {revision}
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */


/**
 * allocation of space to the user is being set here
 */
$user_guid = get_input('user_guid');
$space = get_input('space', 0);
$user = get_user($user_guid);
if ($user) {
  $user->izap_disk_quota = $space;
  system_message(elgg_echo('izap-diskquota:space_allocated_successfully'));
}
forward($_SERVER['HTTP_REFERER']);
exit;