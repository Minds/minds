<?php
    /**
    * Elgg Membership plugin
    * Membership Paypal Settings page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $plugin_settings = $CONFIG->plugin_settings;
    if($plugin_settings) {
        $settings_guid = $plugin_settings->guid;
    }
    $hidden_groups = $plugin_settings->payment_type;
    if (!$hidden_groups) {
        $hidden_groups = 'paypal';
    }
?>
<form id='membership_settings' action="<?php echo $vars['url']; ?>action/membership/settings" method="post">
    <div  id='member_paypal_details'  class="member_paypal">
        <?php echo elgg_view('input/securitytoken'); ?>
        <p>
            <?php
                echo elgg_echo('membership:paypalid');
                echo elgg_view('input/text',array('name'=>'params[paypal_email]','value'=>$plugin_settings->paypal_email));
            ?>
        </p>
        <p>
            <?php
                echo elgg_view('input/radio',array('name' => 'params[payment_type]','options'=>array(
                        'Paypal' => 'paypal',
                        'Sandbox' => 'sandbox'
                        ),
                        'value' => $hidden_groups));
            ?>
        </p>
        <p>
            <input type="hidden" value="<?php echo $settings_guid;?>" name="guid"/>
            <?php
            echo elgg_view('input/securitytoken');
            echo elgg_view('input/submit', array('value' => elgg_echo('save')));
            ?>
        </p>
    </div>
</form>