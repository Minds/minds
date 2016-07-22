<?php
/**
 * Socket.io Emitter
 */
namespace Minds\Core\Sockets;

use Minds\Core\Di\Di;
use Minds\Core\Config;
use Minds\Entities\User;

class Events
{
    private $redis;
    private $msgpack;
    private $prefix = 'socket.io';
    private $rooms = [];
    private $flags = [];

    const EMITTER_UID = '$MINDS_ENGINE_EMITTER';
    const EVENT = 2;
    const BINARY_EVENT = 5;
    const LIVE_ROOM_NAME = 'live';

    public function __construct($redis = null, $msgpack = null)
    {
        $this->redis = $redis ?: Di::_()->get('PubSub\Redis');
        $this->msgpack = $msgpack ?: new MsgPack();
        $this->prefix = (isset($config['socket-prefix']) ? $config['socket-prefix'] : 'socket.io') . '#';
    }

    public function emit(/*$event, ...$data*/)
    {
        $args = func_get_args();

        if (count($args) < 1) {
            throw new \Exception('Missing first argument in emit()');
        }

        $packet = [];
        $packet['type'] = self::EVENT;

        // Handle Binary arguments
        for ($i = 0; $i < count($args); $i++) {
            $arg = $args[$i];
            if ($arg instanceof Binary) {
                $args[$i] = strval($arg);
                $this->setFlag('binary', true);
            }
        }

        if ($this->getFlag('binary')) {
            $packet['type'] = self::BINARY_EVENT;
        }

        $packet['data'] = $args;

        // Namespace
        if (isset($this->flags['nsp'])) {
            $packet['nsp'] = $this->flags['nsp'];
            unset($this->flags['nsp']);
        } else {
            $packet['nsp'] = '/';
        }

        // Options
        $packetOpts = [ 'flags' => $this->flags ];

        if ($this->rooms) {
            $packetOpts['rooms'] = $this->rooms;
        }

        // Pack
        $packed = $this->msgpack->pack([
            static::EMITTER_UID,
            $packet,
            $packetOpts
        ]);

        // Buffer extensions for msgpack with binary
        if ($packet['type'] == self::BINARY_EVENT) {
            $packed = str_replace(pack('c', 0xda), pack('c', 0xd8), $packed);
            $packed = str_replace(pack('c', 0xdb), pack('c', 0xd9), $packed);
        }

        // Publish
        $this->redis->publish($this->prefix . $packet['nsp'] . '#', $packed);

        // Reset
        $this->rooms = [];
        $this->flags = [];

        return $this;
    }

    public function setFlag($flag, $value)
    {
        $this->flags[$flag] = $value;
        return $this;
    }

    public function getFlag($flag)
    {
        return isset($this->flags[$flag]) && $this->flags[$flag];
    }

    public function setRoom($room)
    {
        if (!$room) {
            $this->rooms = [];
            return $this;
        }

        $this->rooms = [ $room ];
        return $this;
    }

    public function setRooms(array $rooms)
    {
        $this->rooms = array_unique(array_filter($rooms, 'strlen'));
        return $this;
    }

    public function setUser($user)
    {
        if ($user instanceof User) {
            $user = $user->guid;
        }

        return $this->setRoom(static::LIVE_ROOM_NAME . ':' . $user);
    }

    public function setUsers(array $users)
    {
        $users = array_unique(array_filter($users, 'strlen'));

        return $this->setRooms(array_map(function ($user) {
            if ($user instanceof User) {
                $user = $user->guid;
            }

            return static::LIVE_ROOM_NAME . ':' . $user;
        }, $users));
    }

    public function setNamespace($nsp)
    {
        $this->flags['nsp'] = $nsp;
        return $this;
    }
}
