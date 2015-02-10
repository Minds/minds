<?php

namespace Minds\Core\Events;

/**
 * Data passed to new style plugin event/hook handlers
 */
class Event {
    
    private $data;
    private $return = true;
    
    function __construct($data) {
        $this->data = $data;
    }
    
    public function setResponse($return) {
        $this->return = $return;
    }
    
    public function response() {
        return $this->return; 
    }
}
