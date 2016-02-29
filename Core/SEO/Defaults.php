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

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {

        Manager::setDefaults([
          'title' =>  Core\Config::_()->site_name,
          'description' => Core\Config::_()->site_description,
          'og:title' => Core\Config::_()->site_name,
          'og:url' => Core\Config::_()->site_url,
          'og:description' => Core\Config::_()->site_description,
          'og:app_id' => Core\Config::_()->site_fbAppId,
          'og:type' => 'website',
          'og:image' => Core\Config::_()->site_url . 'assets/logos/medium.png',
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
                'og:url' => Core\Config::_()->site_url . $user->username,
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
                'title' => $activity->title ?: $activity->message,
                'description' => $activity->blurb ?: "@{$activity->ownerObj['username']} on Minds",
                'og:title' => $activity->title ?: $activity->message,
                'og:description' => $activity->blurb ?: "@{$activity->ownerObj['username']} on Minds",
                'og:url' => $activity->getUrl(),
                'og:image' => $activity->custom_type == 'batch' ? $activity->custom_data[0]['src'] : $activity->thumbnail_src,
                'og:image:width' => 2000,
                'og:image:height' => 1000
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
