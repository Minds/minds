<?php
namespace Minds\Core\Queue\RabbitMQ;

use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue\Message;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Messaging queue
 */

class Client implements Interfaces\QueueClient
{
    private $config;
    private $connection;
    private $channel;
    private $queue;
    private $exchange;
    private $binder = "";

    public function __construct($config, AMQPConnection $connection = null)
    {
        $this->config = $config;
        $this->connection = $connection ?: new AMQPConnection(
            'localhost',
            5672,
            'guest',
            'guest'
        );
        $this->setup();
    }

    protected function setup()
    {
        $this->channel = $this->connection->channel();
        register_shutdown_function(function ($channel, $connection) {
            $channel->close();
            $connection->close();
            //error_log("SHUTDOWN RABBITMQ CONNECTIONS");
        }, $this->channel, $this->connection);
    }

    public function setExchange($name = "default_exchange", $type = "direct")
    {
        $this->exchange = $name;
       //also create exchange if doesn't exist
       //name/type/passive/durable/auto_delete
       $this->channel->exchange_declare($this->exchange, $type, false, true, false);

        return $this;
    }

    public function setQueue($name = "", $binder = "")
    {
        if (!$this->exchange) {
            throw new \Exception("setExchange() must be called prio to setQueue");
        }

        if (!$binder) {
            $binder = $name;
        }

        $this->queue = $name;
        $this->binder = $binder;

       //this is idempotent.. it will only be created if it doesn't exist
       //name/passive/durable/exclusive/auto_delete
       list($this->queue, , ) = $this->channel->queue_declare($name, false, true, false, false);
        $this->channel->queue_bind($this->queue, $this->exchange, $this->binder);

        return $this;
    }

    public function send($message)
    {
        $msg = new Message();
        //error_log("\n === NEW MESSAGE FROM MINDS ===");
        $msg = new AMQPMessage($msg->setData($message));
        //error_log("=== AMPQ MESSAGE CONTRUCTED === \n");
        if ($this->connection->isConnected()) {
            $this->channel->basic_publish($msg, $this->exchange, $this->binder);
            //error_log("Published from minds and complete...\n");
        } else {
            //error_log("Not connected.. but tried to send message to channel");
        }
        return $this;
    }

    public function receive($callback)
    {
        $this->channel->basic_consume($this->queue, '', false, true, false, false, function ($message) use ($callback) {
            $callback(new Message($message->body));
        });

        while (count($this->channel->callbacks) && $this->connection->isConnected()) {
            $this->channel->wait();
        }

        return $this;
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
