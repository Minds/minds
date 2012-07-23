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
?>
<!--update default space for all users in admin panel-->
<p>
  <label>
    <?php echo elgg_echo('izap-diskquota:max_allowed_space_per_user'); ?>
    <br />
    <?php
    echo elgg_view('input/text', array(
        'name' => 'params[izap_allowed_diskspace]',
        'value' => IzapBase::pluginSetting(array(
            'name' => 'izap_allowed_diskspace',
            'plugin' => GLOBAL_IZAP_DISKQUOTA_PLUGIN,
            'value' => 10
        )),
    ));
    ?>
  </label>
</p>

