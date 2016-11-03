<?php
/**
 * Payment Customer Entity
 */
namespace Minds\Core\Payments;

use Minds\Core\Di\Di;

class Customer
{

    private $lu;
    private $user;

    private $id;
    private $email;

    private $payment_methods;

    public function __construct($lu = null)
    {
        $this->lu = Di::_()->get('Database\Cassandra\Lookup');
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getId()
    {
        if (!$this->id) {
            $this->lu->get("{$this->user->guid}:payments:customer_id");
        }
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        if ($this->user) {
            $this->lu->set("{$this->user->guid}:payments:customer_id", $id);
        }
        return $this;
    }

    public function getPaymentMethods()
    {
        return $this->payment_methods;
    }

    public function setPaymentMethods($methods)
    {
        $this->payment_methods = $methods;
        return $this;
    }

    public function getPaymentToken(){
        return $this->token;
    }

    public function setPaymentToken($token)
    {
        $this->token = $token;
        return $this;
    }

}
