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
 *  defining global variables
 */
define('GLOBAL_IZAP_DISKQUOTA_PLUGIN', 'izap-diskquota');
define('GLOBAL_IZAP_DISKQUOTA_PAGEHANDLER', 'diskquota');

elgg_register_event_handler('init', 'system', 'func_disk_quota_init');

/**
 *
 * integrates the plugin with izap_elgg_bridge is done here
 */
function func_disk_quota_init() {
  global $CONFIG;
  if (elgg_is_active_plugin(GLOBAL_IZAP_ELGG_BRIDGE)) {
    izap_plugin_init(GLOBAL_IZAP_DISKQUOTA_PLUGIN);
  } else {
    register_error('This plugin needs izap-elgg-bridge');
    disable_plugin(GLOBAL_IZAP_DISKQUOTA_PLUGIN);
  }

  elgg_register_event_handler('create', 'object', 'func_izap_diskquota_increment');
  elgg_register_event_handler('create', 'group', 'func_izap_diskquota_increment');
  elgg_register_event_handler('update', 'object', 'func_izap_diskquota_decrement');
  elgg_register_event_handler('update', 'object', 'func_izap_diskquota_increment');
  elgg_register_event_handler('update', 'group', 'func_izap_diskquota_increment');
  elgg_register_event_handler('delete', 'object', 'func_izap_diskquota_decrement');
  elgg_register_event_handler('delete', 'group', 'func_izap_diskquota_decrement');

  elgg_extend_view('icon/user/default', GLOBAL_IZAP_DISKQUOTA_PLUGIN . '/forms/user_settings');
  elgg_extend_view('icon/user/default', GLOBAL_IZAP_DISKQUOTA_PLUGIN . '/user_status_profile');
  if (!elgg_is_logged_in()) {
    return '';
  } else {
    elgg_extend_view('page/elements/sidebar', GLOBAL_IZAP_DISKQUOTA_PLUGIN . '/user_status_sidebar');
  }
}

/**
 * increment in the disk space allocation
 * @param type $event
 * @param type $object_type
 * @param type $object
 * @return type boolean
 */
function func_izap_diskquota_increment($event, $object_type, $object) {

  // Final check for the right object  
  if (!method_exists($object, 'getOwnerEntity')) {
    return True;
  }

  // subtypes to skip
  $array = array('plugin');
  if (in_array($object->getSubtype(), $array)) {
    return True;
  }
  
  $tidypics_class = array('TidypicsImage');
  if(in_array(get_class($object), $tidypics_class)) {
    return TRUE;
  }

  // disk quota used space increased 
  $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
  $return = $izap_disk_quota->validate();
  if (!$return) {
    register_error(elgg_echo('izap-diskquota:limt_up'));
    forward(REFERER);
  }

  // save file size if any with this object
  if ($return) {
    $object->izap_diskspace_used = $izap_disk_quota->getCurrentUploadSize();
  }
  return $return;
}

/**
 * decreament process in disk space allocation
 * @param type $event
 * @param type $object_type
 * @param type $object
 * @return type boolean
 */
function func_izap_diskquota_decrement($event, $object_type, $object) {

  // Final check for the right object
  if (!method_exists($object, 'getOwnerEntity')) {
    return True;
  }

  //disk qouta used space is released
  $izap_disk_quota = new IzapDiskQuota($object->getOwnerEntity());
  $izap_disk_quota->releaseSpace($object);
}