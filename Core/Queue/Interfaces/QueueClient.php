<?php
namespace Minds\Core\Queue\Interfaces;

/**
 * Queue client interface
 */
interface QueueClient
{
    /**
     * @param string $name
     * @return QueueClient
     */
    public function setQueue($name = "default");

    /**
     * @param string $name
     * @param string $type
     * @return QueueClient
     */
    public function setExchange($name = "default_exchange", $type = "direct");

    /**
     * @param $message
     * @return mixed
     */
    public function send($message);

    /**
     * @param $callback
     * @return mixed
     */
    public function receive($callback);
};
