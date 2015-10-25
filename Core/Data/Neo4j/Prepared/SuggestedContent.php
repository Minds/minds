<?php
/**
 * Prepared query
 */
namespace Minds\Core\Data\Neo4j\Prepared;

use  Minds\Core\Data\Interfaces;

class Suggested implements Interfaces\PreparedInterface{
    
    private $template;
    private $values; 
    
    public function build(){
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
            
    }
    
    public function setQuery($template, $values = array()){
        $this->template = $template;
        $this->values = $values;
        return $this;
    }
    
}
