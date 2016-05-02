<?php
/**
 * Minds encryption keystore
 */

namespace Minds\Plugin\Messenger\Core;

use Minds\Entities;

class Keystore
{

    static public $tmpPrivateKey;

    private $handler;
    private $user;

    public function __construct($handler = NULL)
    {
        $this->handler = $handler ?: new Encryption\OpenSSL();
    }

    public function setUser($user)
    {
        if(is_numeric($user)){
            $user = Entities\Factory::build($user);
        }
        $this->user = $user;
        return $this;
    }

    public function getPrivateKey()
    {
        return $this->user->{"plugin:user_setting:gatherings:privatekey"};
    }

    public function getPublicKey()
    {
        return $this->user->{"plugin:user_setting:gatherings:publickey"};
    }

    public function unlockPrivateKey($password = "", $new_password = "")
    {
         self::$tmpPrivateKey = $this->handler->unlockPrivateKey($this->getPrivateKey(), $password, $new_password);
         return $this;
    }

    public function getUnlockedPrivateKey()
    {
        if(self::$tmpPrivateKey){
            $_SESSION['tmpPrivateKey'] = self::$tmpPrivateKey;
            return self::$tmpPrivateKey;
        }
        //tmp key is stored in the session
        self::$tmpPrivateKey = $_SESSION['tmpPrivateKey'];
        return self::$tmpPrivateKey;
    }

    public function setPublicKey($public)
    {
        $this->user->{"plugin:user_setting:gatherings:publickey"} = $public;
        return $this;
    }

    public function setPrivateKey($private)
    {
        $this->user->{"plugin:user_setting:gatherings:privatekey"} = $private;
        return $this;
    }

    public function save()
    {
        //todo
        $this->user->save();
        return $this;
    }

}
