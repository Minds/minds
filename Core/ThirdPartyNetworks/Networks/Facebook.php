<?php
/**
 * Facebook integration
 */

namespace Minds\Core\ThirdPartyNetworks\Networks;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Facebook\Facebook as FacebookSDK;

class Facebook implements NetworkInterface
{
    private $fb;
    private $credentials = [];

    private $data = [];

    public function __construct($fb = null)
    {
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
        Core\Di\Di::_()->get('ThirdPartyNetworks\Credentials')
            ->set(Core\Session::getLoggedInUser()->guid, 'facebook', [
                'uuid' => $credentials['uuid'],
                'access_token' => $credentials['access_token']
            ]);
        
        return $this;
    }

    /**
     * Drop facebook api credentials
     * @return $this
     */
    public function dropApiCredentials()
    {
        Core\Di\Di::_()->get('ThirdPartyNetworks\Credentials')
            ->drop(Core\Session::getLoggedInUser()->guid, 'facebook', [
                'uuid',
                'access_token'
            ]);
        
        return $this;
    }

    /**
     * Return api credentials
     * @return array
     */
    public function getApiCredentials()
    {
        $this->credentials = Core\Di\Di::_()->get('ThirdPartyNetworks\Credentials')
            ->get(Core\Session::getLoggedInUser()->guid, 'facebook', [
                'uuid',
                'access_token'
            ]);
        
        return $this;
    }

    public function getFb()
    {
        return $this->fb;
    }

    /**
     * Create a post
     * @param object $entity
     * @return boolean
     */
    public function post($entity)
    {
        if ($entity->remind_object) {
            $entity = new Entities\Activity($entity->remind_object);
        }

        if ($entity->title) {
            $this->data['message'] = $entity->title;
        } elseif ($entity->message) {
            $this->data['message'] = $entity->message;
        }

        if ($entity->perma_url) {
            $this->data = array_merge($this->data, [
              'link' => $entity->perma_url,
              'name' => $entity->title,
              'description' => $entity->blurb,
              'picture' => $entity->thumbnail_src
            ]);
        }

        //Custom image posts
        if (($entity->thumbnail_src && !$entity->perma_url) || $entity->custom_type == 'batch') {
            $this->data['url'] = $entity->thumbnail_src;

            if ($entity->custom_type == 'batch') {
                $this->data['url'] = $entity->custom_data[0]['src'];
            }

            if (isset($this->data['message'])) {
                $this->data['caption'] = $this->data['message'];
                unset($this->data['message']);
            }

            $this->fb->post("/{$this->credentials['uuid']}/photos", $this->data, $this->credentials['access_token']);
            return true;
        }

        //Custom video posts
        if ($entity->custom_type == 'video') {

            if (isset($this->data['message'])) {
                $this->data['description'] = $this->data['message'];
                unset($this->data['message']);
            }

            $this->data['title'] = $entity->title;
            $this->data['file_url'] = Core\Config::_()->site_url . "/api/v1/archive/{$entity->custom_data['guid']}/play";
            $this->fb->post("/{$this->credentials['uuid']}/videos", $this->data, $this->credentials['access_token']);
            return true;
        }

        $this->fb->post("/{$this->credentials['uuid']}/feed", $this->data, $this->credentials['access_token']);
        return true;
    }

    /**
     * Export API information for end-user displaying
     * @return array
     */
    public function export()
    {
        return [
            'connected' => isset($this->credentials['access_token']) && $this->credentials['access_token']
        ];
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
        foreach ($edge as $account) {
            $accounts[] = $account->asArray();
        }
        return $accounts;
    }

    public function getPage()
    {
        if ($this->credentials['uuid'] == 'me' || !$this->credentials['uuid']) {
            return false;
        }
        $response = $this->fb->get('/' . $this->credentials['uuid'], $this->credentials['access_token']);
        $user = $response->getGraphUser();
        return $user->asArray();
    }
}
