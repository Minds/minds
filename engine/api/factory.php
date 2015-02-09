<?php
/**
 * The minds API factory
 */
 
namespace minds\api;
class factory{
    
    /**
     * Builds the api controller
     * This is almost like an autoloader
     */
    public static function build($segments){
            
        $method = strtolower($_SERVER['REQUEST_METHOD']);
        
        $route = implode('\\',$segments);
        $loop = count($segments);
        while($loop >= 0){
            
            $offset = $loop -1; 
            if($loop < count($segments)){
                $slug_length = strlen($segments[$offset+1].'\\');
                $route_length = strlen($route);
                $route = substr($route, 0, $route_length-$slug_length);
            }

            $class_name = "\\minds\\pages\\api\\$route";
            if(class_exists($class_name)){

                $handler = new $class_name();
                $pages = array_splice($segments, $loop) ?: array();
                return $handler->$method($pages);
                
            }
            --$loop;
        }
    }
    
    /**
     * PAM checker
     */
    public static function pamCheck(){
        $user_pam = new \ElggPAM('oauth');
        $user_auth_result = $user_pam->authenticate();
        var_dump( $user_auth_result ); exit;        
    }
    
    /**
     * Builds an api response
     * @param array $data
     * 
     */
    public static function response($data = array()){
        
       
        
        $data = array_merge(array(
            'status' => 'success', //should success be assumed?
        ), $data);
        
        ob_end_clean();
        
        header('Content-type: application/json');
        echo json_encode($data);
        
    }
    
    /**
     * Returns the exportable form of the entities
     * @param array $entities - an array of entities
     * @return array - an array of the entities
     */
    public static function exportable($entities, $exceptions = array()){
        foreach($entities as $k => $entity){
            $entities[$k] = $entity->export();
            $entities[$k]['guid'] = (string) $entity->guid; //javascript doesn't like long numbers..
            foreach($exceptions as $exception){
                $entities[$k][$exception] = $entity->$exception;
            }
        }
        return $entities;
    }
    
}
    
