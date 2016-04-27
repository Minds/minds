<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Plugin\Messenger\Controllers\api\v1;

use Minds\Core;
use minds\plugin\gatherings\entities;
use minds\plugin\gatherings\helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class keys implements Interfaces\Api{

    /**
     * Returns the private key belonging to a user
     * @param array $pages
     *
     * API:: /v1/keys
     */
    public function get($pages){

        $response = array();
       // $_SESSION['user'] = new \Minds\Entities\User($_SESSION['user']->guid, false);
        $unlock_password = get_input('password');
        $new_password = get_input('new_password');
        $pub = \elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings');
        $priv = \elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings');

        //legacy password update
        if(helpers\openssl::verify($pub, $priv, $unlock_password) === FALSE){ //hint: this should fail if pswd is set
          $priv = helpers\openssl::temporaryPrivateKey($priv, $unlock_password, $unlock_password);
          \elgg_set_plugin_user_setting('privatekey', $priv, elgg_get_logged_in_user_guid(), 'gatherings');
        }

        $tmp = helpers\openssl::temporaryPrivateKey($priv, $unlock_password, NULL);

        if(!$tmp || !$unlock_password){
          $response['status'] = 'error';
          $response['message'] = "please check your password";
        } else {
            $response['key'] = $tmp;
        }

        return Factory::response($response);

    }

    public function post($pages){

        switch($pages[0]){
            case "setup":
                $response = array();
                $keypair = \Minds\plugin\gatherings\helpers\openssl::newKeypair(get_input('password'));
                //error_log(print_r($_POST,true));
                \elgg_set_plugin_user_setting('publickey', $keypair['public'], elgg_get_logged_in_user_guid(), 'gatherings');
                \elgg_set_plugin_user_setting('option', '1', elgg_get_logged_in_user_guid(), 'gatherings');
                \elgg_set_plugin_user_setting('privatekey', $keypair['private'], elgg_get_logged_in_user_guid(), 'gatherings');

                if(!isset($_GET['download']) || $_GET['download']){
                  $tmp = helpers\openssl::temporaryPrivateKey($keypair['private'], get_input('password'), NULL);
                  $response['key'] = $tmp;
                } else {
                  $tmp = helpers\openssl::temporaryPrivateKey($keypair['private'], get_input('password'), $_GET['unlock_password']);
                }

                break;
            case "unlock":
            default:
              $response = array();

              $unlock_password = get_input('password');
              $new_password = get_input('new_password');
              $pub = \elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings');
              $priv = \elgg_get_plugin_user_setting('privatekey', elgg_get_logged_in_user_guid(), 'gatherings');

              //patch for legacy private key
              if(helpers\openssl::verify($pub, $priv, $unlock_password) === FALSE){ //hint: this should fail if pswd is set
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
              }

              if(!$tmp){
                  $response['status'] = 'error';
                  $response['message'] = "please check your password";
              }
        }

        return Factory::response($response);


    }

    public function put($pages){

        return Factory::response(array());

    }

    public function delete($pages){

        return Factory::response(array());

    }

}
