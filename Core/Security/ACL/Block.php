<?php
/**
 * ACL: BLOCK
 */
namespace Minds\Core\Security\ACL;

use Minds\Core;
use Minds\Entities;

class Block
{
    private static $_;
    private $db;
    private $cacher;

    public function __construct($db = null, $cacher = null)
    {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = new Core\Data\Call('entities_by_time');
        }

        if ($cacher) {
            $this->cacher = $cacher;
        } else {
            $this->cacher = Core\Data\cache\factory::build();
        }
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    /**
     * Return a list of blocked users
     * @param mixed (Entities\User | string) $from
     * @param int $limit
     * @param string $offset
     * @return array
     */
    public function getBlockList($from = null, $limit = 9999, $offset = "")
    {
        if (!$from) {
            $from = Core\Session::getLoggedinUser();
        }

        if ($from instanceof Entities\User) {
            $from = $from->guid;
        }

        $list = $this->db->getRow("acl:blocked:$from", array('limit' => $limit, 'offset' => $offset));
        return array_keys($list);
    }

    /**
     * Check if a user is blocked
     * @param Entities\User $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     * @return boolean
     */
    public function isBlocked($user, $from = NULL)
    {
        if (!$from) {
            $from = Core\Session::getLoggedinUser();
        }

        if ($from instanceof Entities\User) {
            $from = $from->guid;
        }

        if ($user instanceof Entities\User) {
            $user = $user->guid;
        }

        $list = $this->getBlockList($from, 1, $user);
        if (isset($list[0]) && $list[0] == $user) {
            return true;
        }

        return false;
    }

    /**
     * Add a user to the list of blogs
     * @param Entities\User $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     */
    public function block($user, $from)
    {
        if (!$from) {
            $from = Core\Session::getLoggedinUser();
        }

        if ($from instanceof Entities\User) {
            $from = $from->guid;
        }

        if ($user instanceof Entities\User) {
            $user = $user->guid;
        }

        return $this->db->insert("acl:blocked:$from", array($user => time()));
    }

    /**
     * Removes user to the list of blogs
     * @param Entities\User $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     */
    public function unBlock($user, $from)
    {
        if (!$from) {
            $from = Core\Session::getLoggedinUser();
        }

        if ($from instanceof Entities\User) {
            $from = $from->guid;
        }

        if ($user instanceof Entities\User) {
            $user = $user->guid;
        }

        return $this->db->removeAttributes("acl:blocked:$from", array($user));
    }

    /**
     * Listen to the acl
     */
    public function listen()
    {
        Core\Events\Dispatcher::register('acl:interact', 'all', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];

        if ($this->isBlocked($user, $entity->owner_guid)) {
            $e->setResponse(false);
        }

        });
    }

    public static function _()
    {
        if (!self::$_) {
            return new Block();
        }
        return self::$_;
    }
}
