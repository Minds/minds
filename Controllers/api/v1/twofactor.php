<?php
/**
 * Minds Two Factor
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Security;

class twofactor implements Interfaces\Api, Interfaces\ApiIgnorePam{

    /**
     * NOT AVAILABLE
     */
    public function get($pages){

        $response = [];

        $user = Core\Session::getLoggedInUser();
        $response['telno'] = $user->telno;

        return Factory::response($response);

    }

    /**
     * Registers a user
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Create a new channel",
     *     path="/v1/register",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages){
        if(!Core\Security\XSRF::validateRequest()){
            return false;
        }

        $twofactor = new Security\TwoFactor();
        $user = Core\Session::getLoggedInUser();
        $response = [];

        if(!isset($pages[0])){
            $pages[0] = 'authenticate';
        }


        switch($pages[0]){
            case "setup":

                $secret = $twofactor->createSecret();
                if(\Minds\plugin\guard\start::sendSMS($_POST['tel'], $twofactor->getCode($secret))){
                    $response['secret'] = $secret;
                } else {
                    $response['status'] = "error";
                    $response['message'] = "Invalid number";
                }

                break;
            case "check":
                $secret = $pages[1];
                $code = $_POST['code'];
                $telno = $_POST['telno'];
                if($twofactor->verifyCode($secret, $code, 1)){
                    $response['status'] = "success";
                    $response['message'] = "2factor now setup";
                    $user->twofactor = true;
                    $user->telno = $telno;
                } else {
                    $response['status'] = "error";
                    $response['message'] = "2factor code failed";
                    $user->twofactor = false;
                }
                $user->save();
                break;
            case "authenticate":

            		//get our one user twofactor token
            		$lookup = new Core\Data\lookup('twofactor');
            		$return = $lookup->get($_POST['token']);
            		$lookup->remove($pages[0]);

            		//we allow for 120 seconds (2 mins) after we send a code
            		if($return['_guid'] && $return['ts'] > time()-120){
            			$user = new Entities\User($return['_guid']);
            			$secret = $return['secret'];
            		}else {
                  header('HTTP/1.1 401 Unauthorized', true, 401);
                  $response['status'] = 'error';
                  $response['message'] = 'Invalid token';
            		}

            		if($twofactor->verifyCode($secret, $_POST['code'], 1)){

            			global $TWOFACTOR_SUCCESS;
            			$TWOFACTOR_SUCCESS = true;
            			\login($user, true);

                  $response['status'] = 'success';
                  $response['user'] = $user->export();

            		} else {
                  header('HTTP/1.1 401 Unauthorized', true, 401);
                  $response['status'] = 'error';
                  $response['message'] = 'Could not verify.';
            		}
                break;
        }

        return Factory::response($response);

    }

    public function put($pages){}

    public function delete($pages){
        $user = Core\Session::getLoggedInUser();
        $user->twofactor = false;
        $user->telno = false;
        $user->save();
        return Factory::response([]);
    }

}
