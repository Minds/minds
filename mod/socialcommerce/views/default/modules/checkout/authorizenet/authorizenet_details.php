<?php
	/*****************************************************************************\
	+-----------------------------------------------------------------------------+
	| Elgg Socialcommerce Plugin                                                  |
	| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
	| All rights reserved.                                                        |
	+-----------------------------------------------------------------------------+
	| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
	| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
	| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
	|                                                                             |
	| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
	| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
	| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
	| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
	| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
	| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
	| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
	| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
	| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
	| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
	| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
	| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
	| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
	| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
	|                                                                             |
	+-----------------------------------------------------------------------------+
	\*****************************************************************************/

	/**
	 * Elgg view - Authorize.net Details
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
?>

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
                else
                {
                        return true;

                }
        }

</script>


                <div class="authorizenet_back">

                    <div><h3 style="padding: 5px;"><?php echo elgg_echo('Authorize.net Details') ?></h3></div>

                    <div>
                        <div class="authorizenet_head"><b><?php echo elgg_echo('Payment Information') ?></b></div>

                        <p>
                                <strong><?php echo elgg_echo('Credit Card Number') ?></strong>
                                <input type="text" name="credit_card_number" id="credit_card_number" class="input_auth" maxlength="16" />
                        </p>
                        <p>
                                <strong><?php echo elgg_echo('Security Code (CVV)') ?></strong>
                                <input type="text" name="security_code" id="security_code" class="input_auth" maxlength="4" onkeypress="return isNumberKey(event)" />
                        </p>
                        <p>
                                <strong><?php echo elgg_echo('Expiration Date') ?></strong>
                                <select name="expiration_month" id="expiration_month">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>

                                <select name="expiration_year" id="expiration_year">
                                <?php
                                    $current_year = date("Y");
                                    $stop_year = $current_year + 12;
                                    for ($year = $current_year; $year <= $stop_year; $year++)
                                    {
                                ?>
                                    <option value="<?php echo $year ?>"><?php echo $year ?></option>
                                <?php
                                    }
                                ?>
                                </select>
                        </p>
                    </div>

                    <div>
                        <div class="authorizenet_head"><b><?php echo elgg_echo('Billing Information') ?></b></div>

                        <p>
                            <strong><?php echo elgg_echo('First Name') ?></strong>
                            <input type="text" name="bill_first_name" id="bill_first_name" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->firstname; ?>" />
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Last Name') ?></strong>
                            <input type="text" name="bill_last_name" id="bill_last_name" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->lastname; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Billing Address 1') ?></strong>
                            <input type="text" name="billing_address1" id="billing_address1" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->address_line_1; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Billing Address 2') ?></strong>
                            <input type="text" name="billing_address2" id="billing_address2" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->address_line_2; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('City') ?></strong>
                            <input type="text" name="billing_city" id="billing_city" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->city; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('State') ?></strong>
                            <input type="text" name="billing_state" id="billing_state" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->state; ?>"/>
                        </p>
                        <p>
                            <strong><?php echo elgg_echo('Zip Code') ?></strong>
                            <input type="text" name="billing_zip" id="billing_zip" class="input_auth" value="<?php echo $_SESSION['CHECKOUT']['billing_address']->pincode; ?>"/>
                        </p>
                    </div>
					<?php echo elgg_view('input/submit', array('name' => 'confirm', 'value' => elgg_echo('checkout:confirm:btn'), 'onclick'=>"return formvalidation();"));?>
                    
                </div>
		<div style="clear:both;"></div>
