<?php
/**
 * Get guids from our ZMQ servers, with fallback if we can't connect in time
 */
namespace Minds\Core;

class Guid{

    static $socket;

    static function build(){
        if(!self::$socket){
            self::$socket = self::connect();
        }
        self::$socket->send('GEN');
        return $socket->recv();
    }

    static function connect(){
        global $CONFIG;
        $port = 5599; 
        
        $socket = new \ZMQSocket(new \ZMQContext(), \ZMQ::SOCKET_REQ);
        $socket->connect("tcp://localhost:{$port}");
        $socket->setSockOpt(\ZMQ::ZMQ_RCVTIMEO, 500);
        $socket->setSockOpt(\ZMQ::ZMQ_SNDTIMEO, 500);
        return $socket;
    }

}
