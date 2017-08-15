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

        /* Trigger SEO providers */
        (new Core\Media\SEO())->setup();
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
          'og:image' => $this->config->site_url . 'assets/share/master.jpg',
          'og:image:width' => 1024,
          'og:image:height' => 681
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

                if (!$user->enabled || $user->banned == 'yes') {
                    header("HTTP/1.0 404 Not Found");
                    return [
                        'robots' => 'noindex'
                    ];
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
                    header("HTTP/1.0 404 Not Found");
                    return [
                      'robots' => 'noindex'
                    ];
                }
                if ($activity->paywall) {
                    return;
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

        /**
         * Pages
         */
        Manager::add('/p', function ($slugs = []) {
            if (isset($slugs[0])) {
                try {
                    $page = (new Entities\Page())
                        ->loadFromGuid($slugs[0]);
                } catch (\Exception $e) {
                    header("HTTP/1.0 404 Not Found");
                    return [
                      'robots' => 'noindex'
                    ];
                }

                $meta = [
                  'title' => $page->getTitle(),
                  'description' => substr(strip_tags($page->getBody()), 0, 140),
                  'og:title' => $page->getTitle(),
                  'og:description' => substr(strip_tags($page->getBody()), 0, 140),
                  'og:url' => $this->config->site_url . 'p/' . $page->getPath()
                ];

                if ($page->getHeader()) {
                    $meta['og:image'] = $this->config->site_url . 'fs/v1/pages/' . $page->getPath();
                    $meta['og:image:width'] = 2000;
                    $meta['og:image:height'] = 1000;
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

        $marketing = [
            'affiliates' => [
                'title' => 'Affiliate Program',
                'description' => 'Earn 25% of the revenue Minds generates from your referrals',
                'image' => 'assets/photos/balloon.jpg'
            ],
            'monetization' => [
                'title' => 'Monetization',
                'description' => 'Start earning revenue on Minds by monetizing your channel',
                'image' => 'assets/photos/sunset.jpg'
            ],
            'plus' => [
                'title' => 'Minds Plus',
                'description' => 'Opt-out of boosts, earn 1,000 monthly points, access exclusive Minds content, and more',
                'image' => 'assets/photos/fractal.jpg'
            ],
            'wallet' => [
                'title' => 'Wallet',
                'description' => 'Your Wallet keeps track of your points, payouts, and how much money you’ve earned on Minds.',
                'image' => 'assets/screenshots/register.png'
            ],
            'wire' => [
                'title' => 'Wire',
                'description' => 'Exchange points, dollars and Bitcoin directly with other channels on Minds',
                'image' => 'assets/screenshots/blown-bulb.jpg'
            ]
        ];

        foreach ($marketing as $uri => $page) {
            Manager::add("/$uri", function ($slugs = []) use ($uri, $page) {
                $meta = [
                    'title' => $page['title'],
                    'description' => $page['description'],
                    'og:title' => $page['title'],
                    'og:description' => $page['description'],
                    'og:url' => $this->config->site_url . $uri,
                    'og:image' => $this->config->site_url . $page['image'],
                    'og:image:width' => 2000,
                    'og:image:height' => 1000
                ];
                return $meta;
            });
        }
    }

    public static function _()
    {
        if (!self::$_) {
            self::$_ = new Defaults();
        }
        return self::$_;
    }
}
