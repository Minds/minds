<?php

namespace Minds\Api;
use Minds\Interfaces;
use Minds\Helpers;
use Minds\Core\Security;
use Minds\Core\Session;
/**
 * The minds API factory
  */
class Factory{

    /**
     * Builds the api controller
     * This is almost like an autoloader
     */
    public static function build($segments){
        try{
        Helpers\RequestMetrics::increment('api');
        } catch(\Exception $e){}

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

            //Literal routes
            $actual = str_replace('\\', '/', $route);
            if(isset(Routes::$routes[$actual])){
                $class_name = Routes::$routes[$actual];
                if(class_exists($class_name)){
                    $handler = new $class_name();
                    if($handler instanceof Interfaces\ApiAdminPam)
                      self::adminCheck();
                    if(!$handler instanceof Interfaces\ApiIgnorePam)
                        self::pamCheck();
                    $pages = array_splice($segments, $loop) ?: array();
                    return $handler->$method($pages);

                }
            }

            //autloaded routes
            $class_name = "\\minds\\pages\\api\\$route";
            if(class_exists($class_name)){
                $handler = new $class_name();
                if($handler instanceof Interfaces\ApiAdminPam)
                    self::adminCheck();
                if(!$handler instanceof Interfaces\ApiIgnorePam)
                    self::pamCheck();
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
	    //error_log("checking pam");
        $user_pam = new \ElggPAM('user');
        $api_pam = new \ElggPAM('api');
        $user_auth_result = $user_pam->authenticate();
        if($user_auth_result && $api_pam->authenticate() || Security\XSRF::validateRequest()){

        } else {
             error_log('failed authentication:: OAUTH via API');
             ob_end_clean();
             header('Content-type: application/json');
             header("Access-Control-Allow-Origin: *");
             header('HTTP/1.1 401 Unauthorized', true, 401);
             echo json_encode(array('error'=>'Sorry, you are not authenticated', 'code'=>401));
             exit;

        }
    }

    /**
     * Check if a user is an admin
     */
    private static function adminCheck(){
      if(Session::isLoggedIn() && Session::getLoggedinUser()->isAdmin()){
        return true;
      } else {
        error_log('security: unauthorized access to admin api');
        ob_end_clean();
        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        header('HTTP/1.1 401 Unauthorized', true, 401);
        echo json_encode(array('error'=>'You are not an admin', 'code'=>401));
        exit;
      }
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
        header("Access-Control-Allow-Origin: *");
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
            if(isset($entities[$k]['ownerObj']['guid']))
                $entities[$k]['ownerObj']['guid'] = (string) $entity->ownerObj['guid'];
            foreach($exceptions as $exception){
                $entities[$k][$exception] = $entity->$exception;
            }
        }
        return $entities;
    }

}
