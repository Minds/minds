<?php
/**
 * Minds Settings API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;

class settings implements interfaces\api{

    /**
     * Extended channel
     *
     * @SWG\GET(
     *     summary="Return settings",
     *     path="/v1/settings",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function get($pages){

      if(Core\Session::getLoggedInUser()->isAdmin() && isset($pages[0]))
        $user = new entities\User($pages[0]);
      else
        $user = Core\Session::getLoggedInUser();

      $response = array();

      $response['channel'] = $user->export();
      $response['channel']['email'] = $user->getEmail();

      return Factory::response($response);

    }

    /**
     * Registers a user
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Update settings",
     *     path="/v1/settings",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages){
        if(!Core\Security\XSRF::validateRequest()){
            return false;
        }

        if(Core\Session::getLoggedInUser()->isAdmin() && isset($pages[0]))
          $user = new entities\User($pages[0]);
        else
          $user = Core\Session::getLoggedInUser();

        if(isset($_POST['name']) && $_POST['name']){
          $user->name = $_POST['name'];
        }

        if(isset($_POST['email']) && $_POST['email']){
          $user->setEmail($_POST['email']);
        }

        if(isset($_POST['password']) && $_POST['password']){
          if(!Core\Security\Password::check($user, $_POST['password'])){
            return Factory::response(array(
              'status' => 'error',
              'message' => 'You current password is incorrect'
            ));
          }
          //need to create a new salt and hash...
          $user->salt = Core\Security\Password::salt();
          $user->password = Core\Security\Password::generate($user, $_POST['new_password']);
          $user->override_password = true;
        }

        $response = array();
        if(!$user->save())
          $response['status'] = 'error';

        return Factory::response($response);

    }

    public function put($pages){
      return Factory::response(array());
    }

    public function delete($pages){
      return Factory::response(array());
    }

}
