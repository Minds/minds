<?php
/**
 * Get guids from our ZMQ servers, with fallback if we can't connect in time
 */
namespace Minds\Core;

class Guid
{
    public static $socket;

    public static function build()
    {
        $guid = null;
        //use ZMQ id generator if we can
        if (class_exists('\ZMQContext')) {
            if (!self::$socket) {
                self::$socket = self::connect();
            }
            try {
                self::$socket->send('GEN');
                $guid = self::$socket->recv();
            } catch (\Exception $e) {
                error_log("Could not connect to GUID server, conflicts possible");
            }
        }
        if (!$guid) {
            $g = new \GUID();
            $guid = $g->generate();
            ;
        }
        return $guid;
    }

    public static function connect()
    {
        global $CONFIG;
        $port = 5599;
        
        $socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_REQ);
        $socket->connect("tcp://localhost:{$port}");
        $socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);
        $socket->setSockOpt(\ZMQ::SOCKOPT_RCVTIMEO, 500);
        $socket->setSockOpt(\ZMQ::SOCKOPT_SNDTIMEO, 500);
        return $socket;
    }
}
