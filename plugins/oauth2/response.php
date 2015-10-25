<?php
/**
 * Response extension.. so we can error log when we need to.
 */
namespace minds\plugin\oauth2;

use OAuth2;

class response extends OAuth2\Response{

 
    public function setError($statusCode, $name, $description = null, $uri = null){
        parent::setError($statusCode, $name, $description, $uri);
        
 //       error_log("OAuth-DEBUG:: Status: $statusCode. Name: $name. Description:". print_r($description, true));
        
    }
   
}
