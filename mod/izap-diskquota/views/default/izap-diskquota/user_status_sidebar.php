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

//checks if user is logged in
if (!elgg_is_logged_in() && elgg_get_logged_in_user_guid() != elgg_get_page_owner_guid()) {
  return '';
}
$izap_diskspace = new IzapDiskQuota(elgg_get_logged_in_user_entity());
?>
<!--displays disk space status-->
<div class="izap_diskspace_user_status">
  <div class="outer_wrapper">
    <?php
    $mb = $izap_diskspace->getUserUsedSpaceInMB();
    echo elgg_echo('izap-diskquota:diskstatus') . ' ';
    echo $mb . 'Mb';
    echo ' (';
    $percent = $izap_diskspace->getUserUsedSpaceInPercent();
    echo $percent . '%';
    echo ') ';
    echo elgg_echo('izap-diskquota:of') . ' ';
    echo $izap_diskspace->getUserDiskquotaInMB() . 'Mb';
    ?>
    <div class="inner_wrapper" style="width:<?php echo ($percent > 100) ? 100 : $percent ?>%;<?php echo ($percent > 80) ? 'background-color:#D70303;' : '' ?> ;">
    </div>
  </div>
</div>