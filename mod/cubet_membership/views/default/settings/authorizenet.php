<?php
    /**
    * Elgg Membership plugin
    * Membership Authorize.net Settings page
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
    $hidden_account = $plugin_settings->authorizenet_environment;
    if (!$hidden_account) {
        $hidden_account = 'no';
    }
?>
<form id='membership_settings' action="<?php echo $vars['url']; ?>action/membership/settings" method="post">
    <div id='member_paypal_details' class="member_authorizenet">
        <div>
            <script language="javascript">
            function toggle() {
                var ele = document.getElementById("toggleText");
                var text = document.getElementById("displayText");
                if(ele.style.display == "block") {
                    ele.style.display = "none";
                    text.innerHTML = "<?php echo elgg_echo('authorizenet'); ?>";
                }
                else {
                    ele.style.display = "block";
                    text.innerHTML = "<?php echo elgg_echo('authorizenet'); ?>";
                }
            }
            </script>

            <a id="displayText" href="javascript:toggle();"><?php echo elgg_echo('authorizenet'); ?></a>
            <div id="toggleText" style="display: none">
                <?php echo elgg_echo('authorizenet:instruction'); ?>
            </div>
        </div>
        <br/>

        <div>
            <h4><?php echo elgg_echo('settings'); ?></h4>
            <div>
                <p>
                    <?php echo elgg_echo('api:login:id'); ?>
                    <?php echo elgg_view('input/text',array('name'=>'params[authorizenet_apiloginid]','value'=>$plugin_settings->authorizenet_apiloginid)); ?>
                </p>

                <p>
                    <?php echo elgg_echo('transaction:key'); ?>
                    <?php echo elgg_view('input/text',array('name'=>'params[authorizenet_transactionkey]','value'=>$plugin_settings->authorizenet_transactionkey)); ?>
                </p>

                <p>
                    <?php echo elgg_echo('test:account'); ?>
                    <?php
                            echo elgg_view('input/radio',array('name' => 'params[authorizenet_environment]','options'=>array(
                                    'Yes' => 'yes',
                                    'No' => 'no'
                                    ),
                                    'value' => $hidden_account));

                    ?>
                </p>
                <div class="clear"></div>
            </div>
        </div>
        <p>
            <input type="hidden" value="<?php echo $settings_guid;?>" name="guid"/>
            <?php
            echo elgg_view('input/securitytoken');
            echo elgg_view('input/submit', array('value' => elgg_echo('save')));
            ?>
        </p>
    </div>
    
</form>