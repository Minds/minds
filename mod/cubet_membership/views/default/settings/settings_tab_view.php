<?PHP
   /**
    * Elgg Membership plugin
    * Membership settings tab view page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
    $filter = $vars['filter'];
    if(!is_array($CONFIG->plugin_settings->show_checkout)) {
        $show_checkouts = array($CONFIG->plugin_settings->show_checkout);
    } else {
        $show_checkouts = $CONFIG->plugin_settings->show_checkout;
    }
?>
<div id="elgg_horizontal_tabbed_nav">
    <ul>
        <li id="general" <?php if($filter == "general") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings";?>">General</a>
        </li>
        <li id="premium" <?php if($filter == "premium") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings/premium"; ?>">Premium</a>
        </li>
        <li id="coupon" <?php if($filter == "coupon") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings/coupon"; ?>"><?php echo elgg_echo('membership:coupon:code')?></a>
        </li>
        <li id="report" <?php if($filter == "report") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings/report"; ?>"><?php echo elgg_echo('Log')?></a>
        </li>
        <?php
        
        if(in_array('authorizenet', $show_checkouts)) {?>
        
        <li id="authorizenet" <?php if($filter == "authorizenet") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings/authorizenet"; ?>"><?php echo elgg_echo('membership:authorizenet')?></a>
        </li>
        <?php } ?>
        <?php if(in_array('paypal', $show_checkouts)) {?>
        <li id="paypal" <?php if($filter == "paypal") echo "class='selected'"; ?>>
            <a href="<?php echo "{$CONFIG->wwwroot}membership/settings/paypal"; ?>"><?php echo elgg_echo('membership:paypal')?></a>
        </li>
        <?php } ?>
    </ul>
</div>