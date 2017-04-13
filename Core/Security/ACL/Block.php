<?php
/**
 * ACL: BLOCK
 */
namespace Minds\Core\Security\ACL;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Core\Data\Cassandra;

class Block
{
    private static $_;
    private $db;
    private $cacher;

    public function __construct($db = null, $cql = null, $cacher = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->cacher = $cacher ?: Core\Data\cache\factory::build();
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

        if (!$from) {
            return [];
        }

        $list = $this->db->getRow("acl:blocked:$from", array('limit' => $limit, 'offset' => $offset));
        return $list ? array_keys($list) : array();
    }

    /**
     * Check if a user is blocked
     * @param mixed (Entities\User | array[Entities\User]) $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     * @return boolean
     */
    public function isBlocked($users, $from = null)
    {
        if (!$from) {
            $from = Core\Session::getLoggedinUser();
        }

        if ($from instanceof Entities\User) {
            $from = $from->guid;
        }

        $user_guids = [];
        if (is_array($users)) {
            foreach ($users as $user) {
                if ($users instanceof Entities\User) {
                    $user_guids[] = (string) $user->guid;
                } else {
                    $user_guids[] = (string) $user;
                }
            }
        }

        if ($users instanceof Entities\User) {
            $users = (string) $users->guid;
        }

        if (is_numeric($users)) {
            $user_guids[] = (string) $users;
        }

        if (is_object($from)) { // Unlikely to be an user, and we cannot block anything that's not an user (yet)
            return false;
        }

        $prepared = new Cassandra\Prepared\Custom();
        $collection = \Cassandra\Type::collection(\Cassandra\Type::text())
            ->create(... $user_guids);
        $prepared->query("SELECT * from entities_by_time WHERE key= ? AND column1 IN ? LIMIT ?",
          [ "acl:blocked:$from", $collection, 1000 ]);

        $list = [];
        $result = $this->cql->request($prepared);
        foreach ($result as $item) {
            $list[] = $item['column1'];
        }

        if (is_array($users) ){
            return $list;
        }

        if (isset($list[0]) && $list[0] == $users) {
            return true;
        }

        return false;
    }

    /**
     * Add a user to the list of blogs
     * @param Entities\User $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     */
    public function block($user, $from = null)
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

        if (is_object($from)) { // Unlikely to be an user, and we cannot block anything that's not an user (yet)
            return false;
        }

        Core\Events\Dispatcher::trigger('acl:block', 'all', compact('user', 'from'));

        return $this->db->insert("acl:blocked:$from", array($user => time()));
    }

    /**
     * Removes user to the list of blogs
     * @param Entities\User $user - check if this user is blocked
     * @param mixed (Entities\User | string) - from this user
     */
    public function unBlock($user, $from = null)
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

        Core\Events\Dispatcher::trigger('acl:unblock', 'all', compact('user', 'from'));

        return $this->db->remove("acl:blocked:$from", array($user));
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

            if ($entity->owner_guid && $this->isBlocked($user, $entity->owner_guid)) {
                $e->setResponse(false);
            } elseif ($entity->guid && $this->isBlocked($user, $entity->guid)) {
                $e->setResponse(false);
            } elseif ($entity && $this->isBlocked($user, $entity)) {
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
