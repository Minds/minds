<?php
namespace Minds\Helpers;

use Minds\Core;

/**
 * Helper for exporting
 */
class Export{

    public static function sanitize($array){
        $return = array();
       
        foreach($array as $k => $v){
            if(is_numeric($v) || is_string($v)){
                $return[$k] = (string) $v;
            } elseif(is_bool($v)){
                $return[$k] = $v;
            } elseif(is_object($v) || is_array($v)){
                $return[$k] = self::sanitize($v);
            } else {
                 $return[$k] = $v;
            }        
        }

        return $return; 
    }
         
}   
