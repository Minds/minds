<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Plugin\Messenger\Controllers\api\v1;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Plugin\Messenger;

class keys implements Interfaces\Api
{

    /**
     * Returns the private key belonging to a user
     * @param array $pages
     *
     * API:: /v1/keys
     */
    public function get($pages)
    {
        $response = [];

        $keystore = new Messenger\Core\Keystore();
        $keystore->setUser(Core\Session::getLoggedInUser());
 
        // $_SESSION['user'] = new \Minds\Entities\User($_SESSION['user']->guid, false);
        $unlock_password = get_input('password');
        $new_password = get_input('new_password');
        $pub = $keystore->getPublicKey();
        $priv = $keystore->getPrivateKey();

        //legacy password update
        //if(helpers\openssl::verify($pub, $priv, $unlock_password) === FALSE){ //hint: this should fail if pswd is set
        //  $priv = helpers\openssl::temporaryPrivateKey($priv, $unlock_password, $unlock_password);
        //  \elgg_set_plugin_user_setting('privatekey', $priv, elgg_get_logged_in_user_guid(), 'gatherings');
        //}

        try {
            $keystore->unlockPrivateKey($unlock_password, null);
            $tmp = $keystore->getUnlockedPrivateKey();
        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = "please check your password";
        }

        if (!$tmp || !$unlock_password) {
            $response['status'] = 'error';
            $response['message'] = "please check your password";
        } else {
            $response['key'] = $tmp;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        $openssl = new Messenger\Core\Encryption\OpenSSL();
        $keystore = (new Messenger\Core\Keystore($openssl))
          ->setUser(Core\Session::getLoggedInUser());

        $response = [];

        switch ($pages[0]) {
            case "setup":
                $response = array();
                $keypair = $openssl->generateKeypair($_POST['password']);

                $keystore->setPublicKey($keypair['public'])
                  ->setPrivateKey($keypair['private'])
                  ->save();

                if (!isset($_POST['download']) || $_POST['download']) {
                    $keystore->unlockPrivateKey($_POST['password']);
                    $tmp = $keystore->getUnlockedPrivateKey();
                    $response['key'] = $tmp;
                } else {
                    $unlockPassword = base64_encode(openssl_random_pseudo_bytes(128));
                    $keystore->unlockPrivateKey($_POST['password'], $unlockPassword);
                    $tmp = $keystore->getUnlockedPrivateKey();
                    $response['password'] = urlencode($unlockPassword);
                }

                break;
            case "unlock":
            default:

                try {
                    $unlockPassword = base64_encode(openssl_random_pseudo_bytes(128));
                    $keystore->unlockPrivateKey($_POST['password'], $unlockPassword);
                    $tmp = $keystore->getUnlockedPrivateKey();
                    $response['password'] = urlencode($unlockPassword);
                } catch (\Exception $e) {
                    $response['status'] = 'error';
                    $response['message'] = $e->getMessage();
                    $response['message'] = "please check your password";
                }


              //patch for legacy private key
              /*if(helpers\openssl::verify($pub, $priv, $unlock_password) === FALSE){ //hint: this should fail if pswd is set
                $priv = helpers\openssl::temporaryPrivateKey($priv, $unlock_password, $unlock_password);
                \elgg_set_plugin_user_setting('privatekey', $priv, elgg_get_logged_in_user_guid(), 'gatherings');
              }

              if(!isset($_POST['download']) || (isset($_POST['download']) && $_POST['download'] === 'true')){
                $tmp = helpers\openssl::temporaryPrivateKey(\elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings'), $unlock_password, NULL);
                $response['key'] = $tmp;
              } else {
                $new_pswd = base64_encode(openssl_random_pseudo_bytes(128));
                $tmp = helpers\openssl::temporaryPrivateKey($priv, $unlock_password, $new_pswd);
                $_SESSION['tmp_privatekey'] = $tmp;
                $response['password'] = $new_pswd;
              }*/
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
