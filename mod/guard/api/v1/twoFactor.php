<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\guard\api\v1;

use Minds\Core;
use minds\entities;
use minds\interfaces;
use Minds\Api\Factory;
use Minds\plugin\guard\lib;

class twoFactor implements interfaces\api, interfaces\ApiIgnorePam{

    /**
     * NOT AVAILABLE
     */
    public function get($pages){

        return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));

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

        $twofactor = new lib\twofactor();

    		//get our one user twofactor token
    		$lookup = new Core\Data\lookup('twofactor');
    		$return = $lookup->get($_POST['token']);
    		$lookup->remove($pages[0]);

    		//we allow for 120 seconds (2 mins) after we send a code
    		if($return['_guid'] && $return['ts'] > time()-120){
    			$user = new entities\user($return['_guid']);
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

        return Factory::response($response);

    }

    public function put($pages){}

    public function delete($pages){}

}
