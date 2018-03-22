<?php
/**
 * Minds facebook endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\thirdpartynetworks;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Interfaces;

class facebook implements Interfaces\Api, Interfaces\ApiIgnorePam
{

  /**
   * Get request
   * @param array $pages
   */
  public function get($pages)
  {
      $response = [];

      /** @var Core\ThirdPartyNetworks\Networks\Facebook $facebook */
      $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
      /** @var Core\ThirdPartyNetworks\Facebook\Manager $manager */
      $manager = Core\Di\Di::_()->get('ThirdPartyNetworks\Facebook\Manager');

      switch ($pages[0]) {
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
              $perms = [
                'publish_pages',
                'publish_actions'
              ];

              if (!isset($_GET['no_pages']) || !$_GET['no_pages']) {
                  $perms[] = 'manage_pages';
              }

              $url = $helper->getLoginUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/callback', $perms);
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

              if (Core\Session::isLoggedIn()) {
                  echo "already logged in...";
                  exit;
              }

              $_SESSION['force'] = true;

              forward($manager->getRedirectUrl());
              break;
          case "login-callback":
              try {
                  $helper = $facebook->getFb()->getRedirectLoginHelper();
                  $accessToken = $helper->getAccessToken();

                  $me = $facebook->getFb()->get('/me?fields=id,name,email', $accessToken);
                  $fb_user = $me->getGraphUser();

                  if (!isset($fb_user['email'])) {
                      return $this->get(['login']);
                  }

                  $manager->checkFbAccount($fb_user);

                  $username = $manager->generateUsername($fb_user);
                  $json = json_encode($username);

                  $_SESSION['fb_user'] = $fb_user;

                  echo "<script>window.opener.onSuccessCallback($json); window.close();</script>";
                  exit;
              } catch (\Exception $e) {
                  error_log("[fbreg]: " . $e->getMessage());
                  echo "<script>window.opener.onErrorCallback($e->getMessage()); window.close();</script>";
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

        switch ($pages[0]) {
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
            case "register":
                if (!isset($_POST['username']) || !$_POST['username']) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username must be provided';
                    break;
                }

                if (!isset($_POST['password']) || !$_POST['password']) {
                    $response['status'] = 'error';
                    $response['message'] = 'Password must be provided';
                    break;
                }

                $username = $_POST['username'];
                $password = $_POST['password'];

                /** @var Core\ThirdPartyNetworks\Facebook\Manager $manager */
                $manager = Core\Di\Di::_()->get('ThirdPartyNetworks\Facebook\Manager');
                try {
                    $user = $manager->register($username, $password, $_SESSION['fb_user']);

                    login($user);

                    $response = [
                        'guid' => $user->guid,
                        'user' => $user->export()
                    ];
                } catch (\Exception $e) {
                    $response = ['status' => 'error', 'message' => $e->getMessage()];
                }
                break;
            case "complete-register": //changes username of a facebook account and sends welcome email

                if(!Core\Security\XSRF::validateRequest()){
                    return Factory::response([
                      'status' => 'error',
                      'message' => 'XSRF token not found or does not match'
                    ]);
                }

                $user = Core\Session::getLoggedinUser();

                //check if the requested username now exists
                $lu = new Core\Data\Call('user_index_to_guid');
                if (!isset($_POST['username']) || !$_POST['username']) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username must be provided';
                    break;
                }
                $username = strtolower(preg_replace("/[^[:alnum:]]/u", '', $_POST['username']));
                if ($lu->getRow($username) && $username != strtolower($user->username)) {
                    $response['status'] = 'error';
                    $response['message'] = 'Username exists';
                    break;
                }

                $user->username = $username;
                $user->save();

                //send out email and final signup tasks
                Core\Events\Dispatcher::trigger('register/complete', 'user', [ 'user' => $user ]);

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
        $user = Core\Session::getLoggedInUser();
        switch ($pages[0]) {
            case "login":
                $lu = new Core\Data\Call('user_index_to_guid');
                $lu->removeRow("fb:$user->fb_uuid");

                $user->fb_uuid = null;
                $user->signup_method = 'ex-facebook';
                $user->save();
                break;
            default:
                $facebook = Core\ThirdPartyNetworks\Factory::build('facebook');
                $facebook->dropApiCredentials();
                $user = Core\Session::getLoggedInUser();
                $user->fb = [];
                $user->boostProPlus = false;
                $user->save();
          }
        return Factory::response([]);
    }
}
