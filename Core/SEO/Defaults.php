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
                'title' => $user->name . ' | ' . $this->config->site_name,
                'og:title' =>  $user->name . ' | ' . $this->config->site_name,
                'og:type' => 'website',
                'description' => "Subscribe to @$user->username on {$this->config->site_name}. " . strip_tags($user->briefdescription),
                'og:description' => "Subscribe to @$user->username on {$this->config->site_name}. " . strip_tags($user->briefdescription),
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
                if ($activity->remind_object) {
                    $activity = new Entities\Activity($activity->remind_object);
                }

                $meta = [
                  'title' => $activity->title ?: $activity->message,
                  'description' => $activity->blurb ?: "@{$activity->ownerObj['username']} on {$this->config->site_name}",
                  'og:title' => $activity->title ?: $activity->message,
                  'og:description' => $activity->blurb ?: "@{$activity->ownerObj['username']} on {$this->config->site_name}",
                  'og:url' => $activity->getUrl(),
                  'og:image' => $activity->custom_type == 'batch' ? $activity->custom_data[0]['src'] : $activity->thumbnail_src,
                  'og:image:width' => 2000,
                  'og:image:height' => 1000
                ];

                if ($activity->custom_type == 'video') {
                    $meta['og:type'] = "video";
                    $meta['og:image'] = $activity->custom_data['thumbnail_src'];
                }

                return $meta;
            }
        });

        Manager::add('/register', function ($slugs = []) {
            $meta = [
              'title' => 'Register',
              'description' => $this->config->site_description,
              'og:title' => 'Register',
              'og:description' => $this->config->site_description,
              'og:url' => $this->config->site_url . 'register',
              'og:image' => $this->config->site_url . 'assets/screenshots/register.png',
              'og:image:width' => 2000,
              'og:image:height' => 1000
            ];

            if (isset($_GET['referrer'])) {
                $user = new Entities\User(strtolower($_GET['referrer']));
                if ($user->name) {
                    $meta['title'] = $meta['og:title'] = "Join $user->name on {$this->config->site_name} and get 100 views";
                    $meta['og:url'] = "{$this->config->site_url}register?referrer={$user->username}";
                }
            }

            return $meta;
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
