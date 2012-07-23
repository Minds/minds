<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<?php

    /*
    echo $_POST["credit_card_number"];
    echo $_POST["security_code"];
    echo $_POST["expiration_month"];
    echo $_POST["expiration_year"];

    echo $_POST["bill_first_name"];
    echo $_POST["bill_last_name"];
    echo $_POST["billing_address1"];
    echo $_POST["billing_address2"];
    echo $_POST["billing_city"];
    echo $_POST["billing_state"];
    echo $_POST["billing_zip"];
    */

    // Load Elgg engine
    require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");

    //print_r($_SESSION);
    $cartitems = ($_SESSION['CHECKOUT']['product']);
    //print_r($cartitems); 
    foreach($cartitems as $key=>$cartitem)
    {
        $selectprod = (get_entity($key));
        $selectedproducts .= $selectprod->title.",";
    }
    $selectedproducts = substr($selectedproducts, 0, strlen($selectedproducts)-1);
    
    
    $method = $_SESSION['CHECKOUT']['checkout_method'];
    //Depricated function replace
    $options = array('metadata_name_value_pairs'	=>	array('checkout_method' => 'authorizenet'),
                                    'types'				=>	"object",
                                    'subtypes'			=>	"s_checkout",
                                    'limit'				=>	1,
                            );
    //print_r($options);
    $settings = elgg_get_entities_from_metadata($options);
    //$settings = get_entities_from_metadata('checkout_method',"{$method}",'object','s_checkout',0,1);
    if($settings){
            $settings = $settings[0];
    }

    $total = $_SESSION['CHECKOUT']['total'];
    //$validate_currency = validate_currency($CONFIG->currency_code,$total,'authorizenet');
    $authorizenet_apiloginid = $settings->socialcommerce_authorizenet_apiloginid;
    $authorizenet_transactionkey = $settings->socialcommerce_authorizenet_transactionkey;
    

    //////////////////////////////////////////////////////////////Authorize.net API Connection/////////////////////////////////////////
    
    require("AuthnetAIM_class.php");


    //$user_id = 1;
    $email   = $_SESSION['user']['email'];
    $product = $selectedproducts;
    $business_firstname = $_POST["bill_first_name"];
    $business_lastname  = $_POST["bill_last_name"];
    $business_address   = $_POST["billing_address1"];
    $business_city      = $_POST["billing_city"];
    $business_state     = $_POST["billing_state"];
    $business_zipcode   = $_POST["billing_zip"];
    /*$business_telephone = '800-555-1234';
    $shipping_firstname = 'John';
    $shipping_lastname  = 'Smith';
    $shipping_address   = '100 Business Rd';
    $shipping_city      = 'Big City';
    $shipping_state     = 'NY';
    $shipping_zipcode   = '10101';*/

    $creditcard = $_POST["credit_card_number"];
    $expiration = $_POST["expiration_month"].'-'.$_POST["expiration_year"];
    $total      = $total;
    //$total      = 0.01;
    $cvv        = $_POST["security_code"];
    //$invoice    = substr(time(), 0, 6);
    //$tax        = 0.00;

    $payment = new AuthnetAIM($authorizenet_apiloginid, $authorizenet_transactionkey);
    $payment->setTransaction($creditcard, $expiration, $total, $cvv);
    //$payment->setParameter("x_duplicate_window", 180);
    //$payment->setParameter("x_cust_id", $user_id);
    $payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
    $payment->setParameter("x_email", $email);
    $payment->setParameter("x_email_customer", FALSE);
    $payment->setParameter("x_first_name", $business_firstname);
    $payment->setParameter("x_last_name", $business_lastname);
    $payment->setParameter("x_address", $business_address);
    $payment->setParameter("x_city", $business_city);
    $payment->setParameter("x_state", $business_state);
    $payment->setParameter("x_zip", $business_zipcode);
    //$payment->setParameter("x_phone", $business_telephone);
    /*$payment->setParameter("x_ship_to_first_name", $shipping_firstname);
    $payment->setParameter("x_ship_to_last_name", $shipping_lastname);
    $payment->setParameter("x_ship_to_address", $shipping_address);
    $payment->setParameter("x_ship_to_city", $shipping_city);
    $payment->setParameter("x_ship_to_state", $shipping_state);
    $payment->setParameter("x_ship_to_zip", $shipping_zipcode);*/
    $payment->setParameter("x_description", $product);
    $payment->process();

    if ($payment->isApproved())
    {
        //print_r($payment);

        // Get info from Authnet to store in the database
        echo "code=".$approval_code  = $payment->getAuthCode();
        $avs_result     = $payment->getAVSResponse();
        $cvv_result     = $payment->getCVVResponse();
        echo "id=".$transaction_id = $payment->getTransactionID();
 	echo "text=".$ResponseText 	= $payment->getResponseText();

        // Do stuff with this. Most likely store it in a database.
        // Direct the user to a receipt or something similiar.
    }
    else if ($payment->isDeclined())
    {
        // Get reason for the decline from the bank. This always says,
        // "This credit card has been declined". Not very useful.
        echo $reason = $payment->getResponseText();

        // Politely tell the customer their card was declined
        // and to try a different form of payment.
    }
    else if ($payment->isError())
    {
        // Get the error number so we can reference the Authnet
        // documentation and get an error description.
        echo $error_number  = $payment->getResponseSubcode();
        echo $error_message = $payment->getResponseText();

        // OR

        // Capture a detailed error message. No need to refer to the manual
        // with this one as it tells you everything the manual does.
        echo $full_error_message =  $payment->getResponseMessage();

    }


?>