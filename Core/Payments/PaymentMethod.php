<?php
/**
 * Payment PaymentMethod Entity
 */
namespace Minds\Core\Payments;

class PaymentMethod
{
    private $customer;
    private $payment_method_nonce;
    private $token;
    
    public function __construct()
    {
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
    
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        
        return $this;
    }
    
    public function getPaymentMethodNonce()
    {
        return $this->payment_method_nonce;
    }
    
    public function setPaymentMethodNonce($payment_method_nonce)
    {
        $this->payment_method_nonce = $payment_method_nonce;
        
        return $this;
    }
    
    public function getToken()
    {
        return $this->token;
    }
    
    public function setToken($token)
    {
        $this->token = $token;
        
        return $this;
    }
}
