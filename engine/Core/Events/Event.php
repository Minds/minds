<?php

namespace Minds\Core\Events;

/**
 * Data passed to new style plugin event/hook handlers
 */
class Event {
    
    private $namespace;
    private $event; 
    private $parameters = array();
    private $data;
    private $return = true;
    
    function __construct($data) {
        $this->data = $data;
        if(isset($data['namespace']))
            $this->namespace = $data['namespace'];
        if(isset($data['event']))
            $this->event = $data['event'];
        if(isset($data['parameters']))
            $this->parameters = $data['parameters'];
    }
    
    public function setResponse($return) {
        $this->return = $return;
    }
    
    public function response() {
        return $this->return; 
    }

    /**
     * Return the namespace
     * @return string
     */
    public function getNamespace(){
        return $this->namespace;
    }

    /**
     * Return the event
     * @return string
     */
    public function getEvent(){
        return $this->event;
    }

    /**
     * Return parameters
     * @return array()
     */
    public function getParameters(){
        return $this->parameters;
    }

}
