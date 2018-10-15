<?php
/**
 * Minds encryption keystore
 */

namespace Minds\Core\Messenger;

use Minds\Entities;
use Minds\Core\Messenger;
use Minds\Core\Di\Di;

class Keystore
{
    public static $tmpPrivateKey;

    /** @var OpenSSL $handler **/
    private $handler;

    /** @var Cache $cache **/
    private $cache;

    /** @var Session $sessions **/
    private $sessions;

    /** @var User $user **/
    private $user;

    public function __construct($handler = null, $cache = null, $sessions = null)
    {
        $this->handler = $handler ?: new Messenger\Encryption\OpenSSL();
        $this->cache = $cache ?: Di::_()->get('Cache');
        $this->sessions = $sessions ?: Di::_()->get('Sessions\Manager');
    }

    public function setUser($user)
    {
        if (is_numeric($user)) {
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
        $session = $this->sessions->getSession();
        if (!$session && self::$tmpPrivateKey) {
            return self::$tmpPrivateKey;
        }
        if (self::$tmpPrivateKey) {
            $this->cache->set($session->getId() . ':tmpPrivateKey', self::$tmpPrivateKey); 
            return self::$tmpPrivateKey;
        }
        //tmp key is stored in the session
        self::$tmpPrivateKey = $this->cache->get($session->getId() . ':tmpPrivateKey');
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
