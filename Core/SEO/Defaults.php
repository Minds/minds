<?php
/**
 * Default seo listeners
 */

namespace Minds\Core\SEO;

use Minds\Core;

use Minds\Entities;

class Defaults
{
    private static $_;
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->init();
    }

    public function init()
    {

        Manager::setDefaults([
          'title' =>  $this->config->site_name,
          'description' => $this->config->site_description,
          'og:title' => $this->config->site_name,
          'og:url' => $this->config->site_url,
          'og:description' => $this->config->site_description,
          'og:app_id' => $this->config->site_fbAppId,
          'og:type' => 'website',
          'og:image' => $this->config->site_url . 'assets/logos/medium.png',
          'og:image:width' => 2000,
          'og:image:height' => 1000
        ]);

        /**
         * Channel default SEO roots
         */
        Manager::add('/', function ($slugs = array()) {
          if (isset($slugs[0]) && is_string($slugs[0])) {
              $user = new Entities\User(strtolower($slugs[0]));
              if (!$user->guid) {
                  return array();
              }

              return $meta = [
                'title' => $user->name . ' | Minds',
                'og:title' =>  $user->name . ' | Minds',
                'og:type' => 'website',
                'description' => "Subscribe to @$user->username on Minds. " . strip_tags($user->briefdescription),
                'og:description' => "Subscribe to @$user->username on Minds. " . strip_tags($user->briefdescription),
                'og:url' => $this->config->site_url . $user->username,
                'og:image' => $user->getIconUrl('master'),
                'og:image:width' => 2000,
                'og:image:height' => 1000
              ];
          }
        });

        /**
         * Activity SEO default
         */
        Manager::add('/newsfeed', function ($slugs = []) {
          if (isset($slugs[0]) && is_numeric($slugs[0])) {
              $activity = new Entities\Activity($slugs[0]);
              if (!$activity->guid) {
                  return [];
              }

              return $meta = [
                'title' => $activity->message ?: $activity->title,
                'description' => "@{$activity->ownerObj['name']} on Minds"
              ];
          }
        });
    }

    public static function _()
    {
        if (!self::$_) {
            self::$_ = new Defaults();
        }
        return self::$_;
    }
}
