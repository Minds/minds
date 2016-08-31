<?php
namespace Minds\Core\ThirdPartyNetworks;

use Minds\Core\Di\Di;
use Minds\Core\Data;

class Credentials
{
    protected $db;

    public function __construct($db = null)
    {
        // @todo: Migrate to DI (Data\Cassandra\Indexes)
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    /**
     * Gets a key (or an array of keys) from the stored social
     * network credentials for a Minds user
     * @param  mixed       $user          - Minds user
     * @param  string      $socialNetwork
     * @param  mixed|array $keys          - Single key or array of keys
     * @return mixed|array
     */
    public function get($user, $socialNetwork, $keys)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $data = $this->db->getRow($user . ":thirdpartynetworks:credentials");

        if (is_string($keys)) {
            $attr = $socialNetwork . ':' . $keys;
            return isset($data[$attr]) ? $data[$attr] : null;
        }

        $result = [];

        foreach ($keys as $key) {
            $attr = $socialNetwork . ':' . $key;
            $result[$key] = isset($data[$attr]) ? $data[$attr] : null;
        }

        return $result;
    }

    /**
     * Sets an array of social network credentials data
     * for a Minds user
     * @param  mixed       $user          - Minds user
     * @param  string      $socialNetwork
     * @param  mixed|array $data          - Key/Value-based array of data
     * @return $this
     */
    public function set($user, $socialNetwork, array $data)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $attrs = [];
        foreach ($data as $key => $value) {
            $attrs[$socialNetwork . ':' . $key] = $value;
        }

        $this->db->insert($user . ":thirdpartynetworks:credentials", $attrs);

        return $this;
    }

    /**
     * Drops (erases) an array of keys from the stored social
     * network credentials for a Minds user
     * @param  mixed  $user          - Minds user
     * @param  string $socialNetwork
     * @param  array  $keys          - Array of keys
     * @return $this
     */
    public function drop($user, $socialNetwork, array $keys)
    {
        if (is_object($user)) {
            $user = $user->guid;
        }

        $attrs = array_map(function ($key) use ($socialNetwork) {
            return $socialNetwork . ':' . $key;
        }, $keys);

        $this->db->removeAttributes($user . ":thirdpartynetworks:credentials", $attrs);
        return $this;
    }
}
