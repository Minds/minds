<?php
namespace Minds\Core\Queue\Interfaces;
/**
 * Queue client interface
 */
interface QueueClient{
    
    /**
     * Set the queue to use
     * @return $this
     */
    public function setQueue($name = "default");
    
    public function send($callback);
    
    public function receive($callback);
    
};