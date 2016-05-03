<?php
/**
 * Minds facebook endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\thirdpartynetworks;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class facebook implements Interfaces\Api, Interfaces\ApiIgnorePam
{

  /**
   * Get request
   * @param array $pages
   */
  public function get($pages)
  {
      $response = [];

      $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');

      switch($pages[0]){
          case "login-url":
              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $url = $helper->getLoginUrl('', [
                'manage_pages',
                'publish_pages'
              ]);
              $response['url'] = $url;
              break;
          case "link":
              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $url = $helper->getLoginUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/callback', [
                'manage_pages',
                'publish_pages',
                'publish_actions'
              ]);
              forward($url);
              exit;
              break;
          case "callback":
              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $accessToken = $helper->getAccessToken();
              $facebook->setApiCredentials([
                'uuid' => 'me',
                'access_token' => (string) $accessToken
              ]);
              echo "<script>window.opener.onSuccessCallback(); window.close();</script>";
              exit;
              break;
          case "login":
              $_SESSION['force'] = true;

              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $url = $helper->getReRequestUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/login-callback', [
                'publish_actions',
                'email'
              ]);
              forward($url);
              break;
          case "login-callback":
              try{
                  $helper = $facebook->getFb()->getRedirectLoginHelper();
                  $accessToken = $helper->getAccessToken();

                  $response = $facebook->getFb()->get('/me?fields=id,name,email', $accessToken);
                  $fb_user = $response->getGraphUser();
                  $fb_uuid = $fb_user['id'];

                  if(!isset($fb_user['email'])){
                      return $this->get(['login']);
                  }

                  //@todo move this login to a core class
                  $lu = new Core\Data\Call('user_index_to_guid');
                  $user_guids = $lu->getRow("fb:$fb_uuid");

                  //check if a user matches this uuid from facebook
                  if($user_guids){
                      //login.. this logic should be in a core class
                      $guid = key($user_guids);
                      $user = new Entities\User($guid);
                      if($user->username){
                        login($user);
                        $json = json_encode($user->export());
                        echo "<script>window.opener.onSuccessCallback($json); window.close();</script>"; exit;
                      }
                  } else {
                      //find a username
                      $username = strtolower(preg_replace("/[^[:alnum:]]/u", '', $fb_user['name']));
                      $i = 0;
                      while($lu->get($username) && $i < 2){
                        $i++;
                        $username .= rand(0,5);
                      }
                      $password = base64_encode(openssl_random_pseudo_bytes(128));
                      $user = register_user($username, $password, $fb_user['name'], $fb_user['email'], false);
                      $params = array(
                          'user' => $user,
                          'password' => $_POST['password'],
                          'friend_guid' => "",
                          'invitecode' => ""
                      );
                      elgg_trigger_plugin_hook('register', 'user', $params, true);

                      //again, this should be a core function, not here
                      $lu->insert("fb:$fb_uuid", [ $user->guid => $user->guid ]);

                      login($user);
                      $json = json_encode($user->export());
                      echo "<script>window.opener.onSuccessCallback($json); window.close();</script>"; exit;
                  }
              } catch(\Exception $e){
                var_dump($e->getMessage());
                echo "<script>window.opener.onErrorCallback(); window.close();</script>";
                exit;
              }
              break;
          case "accounts":
              $facebook->getApiCredentials();
              $accounts = $facebook->getAccounts();
              $response['accounts'] = $accounts;
              break;
          case "page":
              $facebook->getApiCredentials();
              $response['page'] = $facebook->getPage();
              break;
      }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = [];

        switch($pages[0]){
            case "select-page":
                $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
                $accessToken = $_POST['accessToken'];
                $id = $_POST['id'];
                $name = $_POST['name'];
                $facebook->setApiCredentials([
                  'uuid' => $id,
                  'access_token' => $accessToken
                ]);
                $user = Core\Session::getLoggedInUser();
                $user->fb = [
                  'uuid' => $id,
                  'name' => $name
                ];
                $user->boostProPlus = true;
                $user->save();
                break;
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
        $facebook->dropApiCredentials();
        $user = Core\Session::getLoggedInUser();
        $user->fb = [];
        $user->boostProPlus = false;
        $user->save();
        return Factory::response(array());
    }
}
