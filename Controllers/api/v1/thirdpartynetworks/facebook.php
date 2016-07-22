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
use Minds\Plugin\Guard\Exceptions\TwoFactorRequired;

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

              if (Core\Session::isLoggedIn()) {
                  echo "already logged in...";
                  exit;
              }

              $_SESSION['force'] = true;

              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $url = $helper->getReRequestUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/login-callback', [
                'email'
              ]);
              forward($url);
              break;
          case "login-callback":

              try {
                  $helper = $facebook->getFb()->getRedirectLoginHelper();
                  $accessToken = $helper->getAccessToken();

                  $me = $facebook->getFb()->get('/me?fields=id,name,email', $accessToken);
                  $fb_user = $me->getGraphUser();
                  $fb_uuid = $fb_user['id'];

                  if (!isset($fb_user['email'])) {
                      return $this->get(['login']);
                  }

                  //@todo move this login to a core class
                  $lu = new Core\Data\Call('user_index_to_guid');
                  $user_guids = $lu->getRow("fb:$fb_uuid");

                  //check if a user matches this uuid from facebook
                  if ($user_guids) {
                      //login.. this logic should be in a core class
                      $guid = key($user_guids);
                      $user = new Entities\User($guid);
                      if ($user->username) {
                          if (!$user->fb_uuid || $user->signup_method != 'facebook') {
                              $user->fb_uuid = $fb_uuid;
                              $user->signup_method = 'facebook';
                              $user->save();
                          }
                          login($user);
                          $export = $user->export();
                          $export['new'] = false;
                          $json = json_encode($export);
                          echo "<script>window.opener.onSuccessCallback($json); window.close();</script>";
                          exit;
                      }
                  } else {

                      //find a username
                      $username = strtolower(preg_replace("/[^[:alnum:]]/u", '', $fb_user['name']));
                      while ($lu->getRow($username)) {
                          $username .= rand(0, 100);
                      }

                      $password = base64_encode(openssl_random_pseudo_bytes(128));
                      $user = register_user($username, $password, $fb_user['name'] ?: $username, $fb_user['email'], false);
                      $params = [
                          'user' => $user,
                          'password' => $_POST['password'],
                          'friend_guid' => "",
                          'invitecode' => ""
                      ];
                      elgg_trigger_plugin_hook('register', 'user', $params, true);
                      Core\Events\Dispatcher::trigger('register', 'user', $params);

                      //pull in avatar
                      $avatar_url = "https://graph.facebook.com/{$fb_uuid}/picture?type=large&width=720&height=720";
                      $user->icontime = $this->saveAvatar($user, $avatar_url);

                      //@todo update analytics to say signedup with facebook

                      $user->fb_uuid = $fb_uuid;
                      $user->signup_method = 'facebook';
                      $user->save();

                      //again, this should be a core function, not here
                      $lu->insert("fb:$fb_uuid", [ $user->guid => $user->guid ]);

                      login($user);
                      $export = $user->export();
                      $export['new'] = true;
                      $json = json_encode($export);
                      echo "<script>window.opener.onSuccessCallback($json); window.close();</script>";
                      exit;
                  }
              } catch (TwoFactorRequired $e) {
                  echo "<script>window.opener.onErrorCallback('Two factor is not supported for facebook right now'); window.close();</script>";
                  exit;
              } catch (\Exception $e) {
                  error_log("[fbreg]: " . $e->getMessage());
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
            case "complete-register": //changes username of a facebook account and sends welcome email

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

    public function saveAvatar($user, $url)
    {
        $icon_sizes = Core\Config::_()->get('icon_sizes');

        $img = file_get_contents($url);
        file_put_contents("/tmp/fb-" . md5($url), $img);

        $files = [];
        foreach ($icon_sizes as $name => $size_info) {
            $resized = get_resized_image_from_existing_file("/tmp/fb-" . md5($url), $size_info['w'], $size_info['h'], $size_info['square'], 0, 0, 0, 0, $size_info['upscale']);

            if ($resized) {
                //@todo Make these actual entities.  See exts #348.
                $file = new \ElggFile();
                $file->owner_guid = $user->guid;
                $file->setFilename("profile/{$user->guid}{$name}.jpg");
                $file->open('write');
                $file->write($resized);
                $file->close();
                $files[] = $file;
            } else {
                // cleanup on fail
                foreach ($files as $file) {
                    $file->delete();
                }
            }
        }

        return time();
    }
}
