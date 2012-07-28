<?php
    /**
    * Elgg Membership plugin
    * Authorize.net payment page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */
    global $CONFIG;
?>

<div class="contentWrapper">

<?php
    $cat_guid=get_input('cat_guid');
    $guid=get_input("guid");

    access_show_hidden_entities(true);
    $entity = get_entity($cat_guid);
    $new_user = get_entity($guid);
    access_show_hidden_entities(false);
    $amount = $entity->amount;
    
    $cc_no = get_input('credit_card_number', '');
    $sec_code = get_input('security_code', '');
    $expiration_month = get_input('expiration_month','');
    $expiration_year = get_input('expiration_year','');
    $bill_first_name = get_input('bill_first_name',$new_user->name);
    $bill_last_name = get_input('bill_last_name','');
    $billing_address1 = get_input('billing_address1','');
    $billing_address2 = get_input('billing_address2','');
    $billing_city = get_input('billing_city','');
    $billing_state = get_input('billing_state','');
    $billing_zip = get_input('billing_zip','');
    
    //$tran_amount=$vars["transaction_amt"];
    
    /*if(elgg_is_logged_in()) {
        $options = array('types'=>'object',
                'subtypes'=>'mem_transaction',
                'owner_guids'=>$guid,
                'limit'=>1,
                'metadata_name_value_pairs' => array('payment_type' => 'authorizenet','payment_status' => "Approved"),
                'metadata_case_sensitive' => false
                );
        $transactions = elgg_get_entities_from_metadata($options);
    }*/
    ?>

    <form action="<?php echo $vars['url']; ?>action/authorizenet" method="post" name="auth_form" id="auth_form">
    
        <script type="text/javascript">

                function isNumberKey(evt)
                {
                         var charCode = (evt.which) ? evt.which : event.keyCode
                         if (charCode > 31 && (charCode < 48 || charCode > 57))
                            return false;

                         return true;
                }


                function formvalidation()
                {
                        var credit_card_number = $("#credit_card_number").val();
                        var security_code = $('#security_code').val();

                        var bill_first_name = $('#bill_first_name').val();
                        var bill_last_name = $('#bill_last_name').val();
                        var billing_address1 = $('#billing_address1').val();
                        var billing_city = $('#billing_city').val();
                        var billing_state = $('#billing_state').val();
                        var billing_zip = $('#billing_zip').val();

                        if(credit_card_number == '')
                        {
                                alert('Enter your credit card number');
                                return false;
                        }
                        else if(security_code == '')
                        {
                                alert('Enter your security code (CVV)');
                                return false;
                        }
                        else if(bill_first_name == '')
                        {
                                alert('Enter your first name');
                                return false;
                        }
                        else if(bill_last_name == '')
                        {
                                alert('Enter your last name');
                                return false;
                        }
                        if(billing_address1 == '')
                        {
                                alert('Enter your address');
                                return false;
                        }
                        else if(billing_city == '')
                        {
                                alert('Enter your city');
                                return false;
                        }
                        else if(billing_state == '')
                        {
                                alert('Enter your state');
                                return false;
                        }
                        else if(billing_zip == '')
                        {
                                alert('Enter your zip code');
                                return false;
                        }
                        else
                        {
                                $("#auth_form").attr("action", "<?php echo $CONFIG->wwwroot."action/authorizenet" ?>");
                                return true;
                        }
                }

        </script>

                <?php
                global $CONFIG;
                $ret_path=$CONFIG->wwwroot;
                $path= $CONFIG->wwwroot.'mod/cubet_membership/actions/membership/return.php';
                ?>

                <div class="member_authorizenet_back">

                    <div><h3 style="padding: 5px;"><?php echo elgg_echo('Authorize.net Details') ?></h3></div>

                    <div>
                        <div class="member_authorizenet_head"><b><?php echo elgg_echo('Payment Information') ?></b></div>

                        <p>
                                <strong><?php echo elgg_echo('Credit Card Number') ?></strong>
                                <input type="text" name="credit_card_number" id="credit_card_number" class="input_auth" maxlength="16" value="<?php echo $cc_no;?>" />
                        </p>
                        <p>
                                <strong><?php echo elgg_echo('Security Code (CVV)') ?></strong>
                                <input type="text" name="security_code" id="security_code" class="input_auth" maxlength="4" value="<?php echo $sec_code;?>" onkeypress="return isNumberKey(event)" />
                        </p>
                        <p>
                                <strong><?php echo elgg_echo('Expiration Date') ?></strong>
                                <select name="expiration_month" id="expiration_month">
                                    <?php
                                        for ($i=1; $i<=12; $i++) {
                                            $i = sprintf("%02s", $i);
                                            if($expiration_month == $i) {
                                                echo "<option value=\"{$i}\" selected>{$i}</option>";
                                            } else {
                                                echo "<option value=\"{$i}\" >{$i}</option>";
                                            }
                                        }
                                    ?>
                                </select>

                                <select name="expiration_year" id="expiration_year">
                                <?php
                                    $current_year = date("Y");
                                    $stop_year = $current_year + 12;
                                    for ($year = $current_year; $year <= $stop_year; $year++)
                                    {

                                         if($expiration_year == $i) {
                                ?>
                                            <option value="<?php echo $year ?>" selected><?php echo $year ?></option>

                                <?php
                                         } else {
                                ?>
                                            <option value="<?php echo $year ?>"><?php echo $year ?></option>

                                <?php
                                         }
                                    }
                                ?>
                                </select>
                        </p>
                    </div>

                    <div>
                        <div class="member_authorizenet_head"><b><?php echo elgg_echo('Billing Information') ?></b></div>

                        <p>
                            <strong><?php echo elgg_echo('First Name') ?></strong>
                            <input type="text" name="bill_first_name" id="bill_first_name" class="input_auth" value="<?php echo $bill_first_name; ?>" />
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Last Name') ?></strong>
                            <input type="text" name="bill_last_name" id="bill_last_name" class="input_auth" value="<?php echo $bill_last_name; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Address 1') ?></strong>
                            <input type="text" name="billing_address1" id="billing_address1" class="input_auth" value="<?php echo $billing_address1; ?>" />
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Address 2') ?></strong>
                            <input type="text" name="billing_address2" id="billing_address2" class="input_auth" value="<?php echo $billing_address2; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('City') ?></strong>
                            <input type="text" name="billing_city" id="billing_city" class="input_auth" value="<?php echo $billing_city; ?>" />
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('State') ?></strong>
                            <input type="text" name="billing_state" id="billing_state" class="input_auth" value="<?php echo $billing_state; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Zip Code') ?></strong>
                            <input type="text" name="billing_zip" id="billing_zip" class="input_auth" value="<?php echo $billing_zip; ?>"/>
                        </p>
                    </div>

                    <div style="width:490px;">
                            <?php
                            /*if($transactions && strtolower($new_user->user_type) !='free') {
                                echo elgg_view('cubet_membership/process_coupon', array('cat_guid'=>$cat_guid, 'guid'=>$guid, 'amount'=>$tran_amount));
                            } else {*/
                                echo elgg_view('cubet_membership/process_coupon', array('cat_guid'=>$cat_guid, 'guid'=>$guid, 'amount'=>$amount));
                            //}
                            ?>
                            <div style="clear:both;"></div>
                    </div>

                    <input style="float:right;" type="submit" class="elgg-button-submit elgg-button" name="confirm" value="<?php echo elgg_echo('pay'); ?>" onclick="return formvalidation();">
                    <input style="float:left;" class="elgg-button elgg-button-submit" type="button" name="cancel" value="Cancel" onclick="return check('<?php echo $guid;?>','<?php echo $ret_path;?>','<?php echo $path;?>');">
                    
                    <input type="hidden" name="user_type" value="<?php echo $cat_guid;?>">
                    <input type="hidden" name="user_id" value="<?php echo $guid;?>">

                </div>
		<div style="clear:both;"></div>
                 <?php echo elgg_view('input/securitytoken'); ?>
    </form>
</div>
<script type="text/javascript">

        function check(guid,ret_path,path){
        $.post(path, { var_param: guid },
                          function(data){
                          window.location=ret_path;
                          });

        }

</script>
