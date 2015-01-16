<?php
/**
 * Warehouse factory
 */
namespace Minds\Core\Data\Warehouse;

class Factory{
    
    /**
     * Build a warehouse job
     */
    public function build($job){
        $job = "\\Minds\\Core\\Data\\Warehouse\\$job";
        if(class_exists($job)){
            return new $job;
        }
    }

}   