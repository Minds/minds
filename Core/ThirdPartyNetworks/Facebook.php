<?php
/**
 * Facebook integration
 */

namespace Minds\Core\ThirdPartyNetworks;

use Minds\Core;
use Minds\Core\Data;
use Facebook\Facebook as FacebookSDK;

class Facebook implements NetworkInterface
{

    private $db;
    private $fb;
    private $credentials = [];

    private $data = [];

    public function __construct($db = NULL, $fb = NULL)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
        $this->fb = $fb ?: new FacebookSDK([
          'app_id' => Core\Config::_()->facebook['app_id'],
          'app_secret' => Core\Config::_()->facebook['app_secret'],
          'default_graph_version' => 'v2.2',
        ]);
    }

    /**
     * Set and save the api credentials
     * @param array $credentials
     * @return $this
     */
    public function setApiCredentials($credentials = [])
    {
        $data = [
          'facebook:uuid' => $credentials['uuid'],
          'facebook:access_token' => $credentials['access_token'],
        ];
        $this->db->insert(Core\Session::getLoggedInUser()->guid . ":thirdpartynetworks:credentials", $data);
        return $this;
    }

    /**
     * Drop facebook api credentials
     * @return $this
     */
    public function dropApiCredentials()
    {
        $this->db->removeAttributes(Core\Session::getLoggedInUser()->guid . ":thirdpartynetworks:credentials", ['facebook:uuid', 'facebook:access_token']);
        return $this;
    }

    /**
     * Return api credentials
     * @return array
     */
    public function getApiCredentials()
    {
        $data = $this->db->getRow(Core\Session::getLoggedInUser()->guid . ":thirdpartynetworks:credentials");
        if(isset($data['facebook:uuid']))
            $this->credentials['uuid'] = $data['facebook:uuid'];
        if(isset($data['facebook:access_token']))
            $this->credentials['access_token'] = $data['facebook:access_token'];
        return $this;
    }

    public function getFb()
    {
        return $this->fb;
    }

    /**
     * Create a post
     * @param object $entity
     * @return $this
     */
    public function post($entity)
    {
        $this->data = array_merge($this->data, [
          'message' => $entity->message,
          'link' => $entity->getUrl(),
          'name' => $entity->title,
          'description' => "blurb",
          'picture' => $entity->thumbnail_src
        ]);

        $this->fb->post("/{$this->credentials['uuid']}/feed", $this->data, $this->credentials['access_token']);
        exit;
    }

    /**
     * Schedule a post
     * @param int $timestamp
     * @return $this
     */
    public function schedule($timestamp)
    {
        $this->data['scheduled_publish_time'] = $timestamp;
        $this->data['published'] = false;
        return $this;
    }

    public function getAccounts()
    {
        $response = $this->fb->get('/me/accounts', $this->credentials['access_token']);
        $accounts = [];
        $edge = $response->getGraphEdge();
        foreach($edge as $account){
          $accounts[] = $account->asArray();
        }
        return $accounts;
    }

    public function getPage()
    {
        if($this->credentials['uuid'] == 'me' || !$this->credentials['uuid']){
            return false;
        }
        $response = $this->fb->get('/' . $this->credentials['uuid'], $this->credentials['access_token']);
        $user = $response->getGraphUser();
        return $user->asArray();
    }

}
