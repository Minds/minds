<?php
/**
 * Minds facebook endpoint
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\thirdpartynetworks;

use Minds\Core;
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
          case "login":
              $helper = $facebook->getFb()->getRedirectLoginHelper();
              $url = $helper->getLoginUrl(Core\Config::_()->site_url . 'api/v1/thirdpartynetworks/facebook/callback', [
                'manage_pages',
                'publish_pages'
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
              break;
          case "accounts":
              $facebook->getApiCredentials();
              $accounts = $facebook->getAccounts();
              $response['accounts'] = $accounts;
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
                $accessToken = $_POST['access_token'];
                $id = $_POST['id'];
                $name = $_POST['name'];
                $facebook->setApiCredentials([
                  'uuid' => $id,
                  'access_token' => $accessToken
                ]);
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
        return Factory::response(array());
    }
}
