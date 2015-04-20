<?php
namespace Minds\Core\Queue\RabbitMQ;
use Minds\Core\Queue\Interfaces;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Messaging queue
 */

class Client implements Interfaces\QueueClient{
    
    private $connection;
    private $channel;
    private $queue = "default_queue";
    private $exchange = "default_exchange";
    
    public function __construct($options = array()){
        $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest', '/');
        $this->channel = $this->connection->channel();
        
        register_shutdown_function(function($channel, $connection){
            $channel->close();
            $connection->close();
            }, $this->channel, $this->connection);
    }
    
    public function setQueue($name = "default_queue"){
        
       $this->queue = $name;
        
       //also create exchange if doesn't exist
       //name/type/passive/durable/auto_delete
       $this->channel->exchange_declare($this->exchange, 'direct', false, true, false);
        
       //this is idempotent.. it will only be created if it doesn't exist
       //name/passive/durable/exclusive/auto_delete
       $this->channel->queue_declare($name, false, true, false, false);
       
       
       $this->channel->queue_bind($this->queue, $this->exchange);
       
       return $this;
    }
    
    public function send($callback){
        $msg = new AMQPMessage('Hello World!');
        $this->channel->basic_publish($msg, $this->exchange);
        return $this;   
    }
    
    public function receive($callback){

        $this->channel->basic_consume($this->queue, '', false, true, false, false, $callback);

        while(count($this->channel->callbacks)) {
            $this->channel->wait(); 
        }
        
    }
           
}   