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

if (elgg_get_context() !== 'profile' || $vars['size'] == 'small')
  return '';

//check if user is logged in
if (!elgg_is_admin_logged_in()) {
  return '';
}
$user = $vars['entity'];
$izap_diskspace = new IzapDiskQuota($user);
ob_start();
?>
<label>
  <!--updates the disk space of user-->
  <?php echo elgg_echo('izap-diskquota:add_space_limit') ?>
  <br />
  <?php
  echo elgg_view('input/text', array(
      'name' => 'space',
      'value' => $izap_diskspace->getUserDiskquotaInMB(),
  ));
  ?>
</label>
<?php
//gets the id of current logged in user
echo elgg_view('input/hidden', array(
    'value' => $user->guid,
    'name' => 'user_guid',
));
$form = ob_get_clean();
$form = elgg_view('input/form', array(
    'body' => $form,
    'action' => IzapBase::getFormAction('set_user_diskspace', GLOBAL_IZAP_DISKQUOTA_PLUGIN)));
?>
<div>
  <?php echo $form; ?>  
</div>