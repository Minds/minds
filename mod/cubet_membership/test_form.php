<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    // Load Elgg engine
    include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
    global $CONFIG;
?>
<form action="<?php echo $CONFIG->wwwroot."membership/silent_form" ?>" method="post">
    <input type="hidden" name="x_response_code" value="1"/>
    <input type="hidden" name="x_response_subcode" value="1"/>
    <input type="hidden" name="x_response_reason_code" value="1"/>
    <input type="hidden" name="x_response_reason_text" value="This transaction has been approved."/>
    <input type="hidden" name="x_auth_code" value=""/>
    <input type="hidden" name="x_avs_code" value="P"/>
    <input type="hidden" name="x_trans_id" value="2164392521"/>
    <input type="hidden" name="x_invoice_num" value=""/>
    <input type="hidden" name="x_description" value=""/>
    <input type="hidden" name="x_amount" value="10.00"/>
    <input type="hidden" name="x_method" value="CC"/>
    <input type="hidden" name="x_type" value="auth_capture"/>
    <input type="hidden" name="x_cust_id" value="1"/>
    <input type="hidden" name="x_first_name" value="John"/>
    <input type="hidden" name="x_last_name" value="Smith"/>
    <input type="hidden" name="x_company" value=""/>
    <input type="hidden" name="x_address" value=""/>
    <input type="hidden" name="x_city" value=""/>
    <input type="hidden" name="x_state" value=""/>
    <input type="hidden" name="x_zip" value=""/>
    <input type="hidden" name="x_country" value=""/>
    <input type="hidden" name="x_phone" value=""/>
    <input type="hidden" name="x_fax" value=""/>
    <input type="hidden" name="x_email" value=""/>
    <input type="hidden" name="x_ship_to_first_name" value=""/>
    <input type="hidden" name="x_ship_to_last_name" value=""/>
    <input type="hidden" name="x_ship_to_company" value=""/>
    <input type="hidden" name="x_ship_to_address" value=""/>
    <input type="hidden" name="x_ship_to_city" value=""/>
    <input type="hidden" name="x_ship_to_state" value=""/>
    <input type="hidden" name="x_ship_to_zip" value=""/>
    <input type="hidden" name="x_ship_to_country" value=""/>
    <input type="hidden" name="x_tax" value="0.0000"/>
    <input type="hidden" name="x_duty" value="0.0000"/>
    <input type="hidden" name="x_freight" value="0.0000"/>
    <input type="hidden" name="x_tax_exempt" value="FALSE"/>
    <input type="hidden" name="x_po_num" value=""/>
    <input type="hidden" name="x_MD5_Hash" value="A375D35004547A91EE3B7AFA40B1E727"/>
    <input type="hidden" name="x_cavv_response" value=""/>
    <input type="hidden" name="x_test_request" value="false"/>
    <input type="hidden" name="x_subscription_id" value="1184228"/>
    <input type="hidden" name="x_subscription_paynum" value="2"/>
    <input type="submit"/>
</form>