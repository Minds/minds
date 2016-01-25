<?php
/**
 * Payment Customer Entity
 */
namespace Minds\Core\Payments;

class Customer
{
    
    private $id;
    private $email;
    
    public function __construct()
    {
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
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
}
