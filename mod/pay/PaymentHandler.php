<?php

/**
 * Define an interface for an OO payment handler.
 */

namespace minds\plugin\pay {
    
    interface PaymentHandler {

	/**
	 * Handle a payment, forwarding to a payment endpoint as appropriate.
	 * @param type $params
	 */
	static function paymentHandler($params);
	
	/**
	 * Callback, called by pay with an order ID.
	 * @param type $order_guid
	 */
	static function paymentHandler_callback($order_guid);
	
	/**
	 * Called by pay to cancel a recurring payment.
	 * @param type $order_guid
	 */
	static function cancelRecurringPaymentCallback($order_guid);
    }
    
}