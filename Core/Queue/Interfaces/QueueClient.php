<?php
namespace Minds\Core\Queue\Interfaces;

/**
 * Queue client interface
 */
interface QueueClient
{
    public function setQueue($name = "default");

    public function setExchange($name = "default_exchange", $type = "direct");
    
    public function send($message);
    
    public function receive($callback);
};
