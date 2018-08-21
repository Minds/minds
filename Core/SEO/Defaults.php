<?php
/**
 * Default seo listeners
 */

namespace Minds\Core\SEO;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;

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
        (new Core\Groups\SEO())->setup();
        (new Core\Blogs\SEO())->setup();
    }

    public function init()
    {
        Manager::setDefaults([
          'title' =>  $this->config->site_name,
          'description' => $this->config->site_description,
          'keywords' => $this->config->site_keywords,
          'og:title' => $this->config->site_name,
          'og:url' => $this->config->site_url,
          'og:description' => $this->config->site_description,
          'og:app_id' => $this->config->site_fbAppId,
          'og:type' => 'website',
          'og:image' => $this->config->cdn_assets_url . 'assets/logos/placeholder.jpg',
          'og:image:width' => 471,
          'og:image:height' => 199,
          'twitter:site' => '@minds',
          'twitter:card' => 'summary',
          'smartbanner:title' => 'Minds',
          'smartbanner:author' => 'Minds.com',
          'smartbanner:price' => 'free',
          'smartbanner:price-suffix-apple' => ' - On the App Store',
          'smartbanner:price-suffix-google' => ' - In Google Play',
          'smartbanner:icon-apple' => $this->config->cdn_assets_url . 'assets/logos/bulb.svg',
          'smartbanner:icon-google' => $this->config->cdn_assets_url . 'assets/logos/bulb.svg',
          'smartbanner:button' => 'VIEW',
          'smartbanner:button-url-apple' => 'https://itunes.apple.com/app/minds-com/id961771928',
          'smartbanner:button-url-google' => 'https://play.google.com/store/apps/details?id=com.minds.mobile',
          'smartbanner:enabled-platforms' => 'android,ios',
        ]);

        /**
         * Channel default SEO roots
         */
        Manager::add('/blog', [$this, 'channelHandler']);
        Manager::add('/', [$this, 'channelHandler']);

        Manager::add('/crypto', function ($slugs = []) {
            return [
                'title' => 'The Minds Token',
                'description' => 'Coming soon',
                'og:title' => 'The Minds Token',
                'og:description' => 'Coming soon',
                'og:url' => '/token',
                'og:image' => $this->config->cdn_assets_url . 'assets/videos/space-1/space.jpg',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];
        });

        /**
         * Activity SEO default
         */
        Manager::add('/newsfeed', function ($slugs = []) {
            if (isset($slugs[0]) && is_numeric($slugs[0])) {
                $activity = new Entities\Activity($slugs[0]);
                if (!$activity->guid || Helpers\Flags::shouldFail($activity)) {
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
                  'og:image:height' => 1000,
                  'twitter:site' => '@minds',
                  'twitter:card' => 'summary',
                  'al:ios:url' => 'minds://activity/' . $activity->guid,
                  'al:android:url' => 'minds://minds/activity/' . $activity->guid,
                  'robots' => $activity->getRating() == 1 ? 'all' : 'noindex',
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
              'og:image:height' => 1000,
              'twitter:site' => '@minds',
              'twitter:card' => 'summary',
            ];

            if (isset($_GET['referrer'])) {
                $user = new Entities\User(strtolower($_GET['referrer']));
                if ($user->name) {
                    $meta['title'] = $meta['og:title'] = "Join $user->name on {$this->config->site_name}";
                    $meta['og:url'] = "{$this->config->site_url}register;referrer={$user->username}";
                }
            }

            return $meta;
        });

        Manager::add('/login', function ($slugs = []) {
            $meta = [
                'title' => 'Login',
                'description' => $this->config->site_description,
                'og:title' => 'Login',
                'og:description' => $this->config->site_description,
                'og:url' => $this->config->site_url . 'login',
                'og:image' => $this->config->cdn_assets_url . 'assets/screenshots/login.png',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];

            return $meta;
        });

        // channels
        Manager::add('/channels', function ($slugs = []) {
            $allowedSections = ['top', 'subscriptions', 'subscribers'];
            $meta = [];

            if(array_search($slugs[0], $allowedSections) !== false) {
                $meta = [
                    'og:url' => Core\Di\Di::_()->get('Config')->site_url . implode('/', $slugs),
                    'og:image' => Core\Di\Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                    'og:image:width' => 1024,
                    'og:image:height' => 681
                ];

                switch($slugs[0]){
                    case 'top':
                        $meta = array_merge([
                                'title' => 'Top Channels',
                                'og:title' => 'Top Channels',
                                'description' => 'List of top channels',
                                'og:description' => 'List of top channels'
                            ], $meta);
                        break;
                    case 'subscriptions':
                        $meta = array_merge([
                            'title' => "Your Subscriptions",
                            'og:title' => "Your Subscriptions",
                            'description' => "Channels you're subscribed to",
                            'og:description' => "Channels you're subscribed to"
                        ], $meta);
                        break;
                    case 'subscribers':
                        $meta = array_merge([
                            'title' => "Your Subscribers",
                            'og:title' => "Your Subscribers",
                            'description' => "Channels who are subscribed to you",
                            'og:description' => "Channels who are subscribed to you"
                        ], $meta);
                        break;
                    default:
                        $meta = [];
                }
            }
            return $meta;
        });

        /**
         * Do not index search results
         */
        Manager::add('/search', function ($slugs = []) {
            return [
                'robots' => 'noindex'
            ];
        });

        Manager::add('/wallet/tokens/transactions', function ($slugs = []) {
            $meta = [
                'title' => 'Transactions Ledger',
                'description' => 'Keep track of your tokens transactions',
                'og:title' => 'Transactions Ledger',
                'og:description' => 'Keep track of your tokens transactions',
                'og:url' => $this->config->site_url . 'wallet/tokens/transactions',
                'og:image' => $this->config->cdn_assets_url . 'assets/photos/graph.jpg',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];

            return $meta;
        });

        Manager::add('/wallet/tokens/contributions', function ($slugs = []) {
            $meta = [
                'title' => 'Contributions Ledger',
                'description' => 'Keep track of your contributions to Minds',
                'og:title' => 'Contributions Ledger',
                'og:description' => 'Keep track of your contributions to Minds',
                'og:url' => $this->config->site_url . 'wallet/tokens/contributions',
                'og:image' => $this->config->cdn_assets_url . 'assets/logos/placeholder-bulb.jpg',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];

            return $meta;
        });

        Manager::add('/wallet/tokens/withdraw', function ($slugs = []) {
            $meta = [
                'title' => 'Token Rewards Withdrawal',
                'description' => 'Withdraw your token rewards',
                'og:title' => 'Login',
                'og:description' => 'Withdraw your token rewards',
                'og:url' => $this->config->site_url . 'wallet/tokens/withdraw',
                'og:image' => $this->config->cdn_assets_url . 'assets/photos/graph.jpg',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];

            return $meta;
        });


        Manager::add('/wallet/tokens/addresses', function ($slugs = []) {
            $meta = [
                'title' => 'Wallet Address Configuration',
                'description' => 'Configure your wallet address',
                'og:title' => 'Wallet Address Configuration',
                'og:description' => 'Configure your wallet address',
                'og:url' => $this->config->site_url . 'wallet/tokens/addresses',
                'og:image' => $this->config->cdn_assets_url . 'assets/photos/graph.jpg',
                'og:image:width' => 2000,
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
            ];

            return $meta;
        });

        $marketing = [
            'plus' => [
                'title' => 'Minds Plus',
                'description' => 'Upgrade your channel for premium features',
                'image' => 'assets/photos/fractal.jpg'
            ],
            'wallet' => [
                'title' => 'Wallet',
                'description' => 'Manage all of your transactions and earnings on Minds',
                'image' => 'assets/photos/graph.jpg'
            ],
            'wire' => [
                'title' => 'Wire',
                'description' => 'Exchange tokens with other channels on Minds',
                'image' => 'assets/photos/blown-bulb.jpg'
            ],
            'branding' => [
                'title' => 'Branding',
                'description' => 'Logos, assets and styling guides',
                'image' => 'assets/logos/placeholder.jpg',
            ],
            'boost' => [
                'title' => 'Boost',
                'description' => 'Boost your channel or content to gain more views and reach new audiences',
                'image' => 'assets/photos/rocket.jpg'
            ],
            'localization' => [
                'title' => 'Localization',
                'description' => 'Help translate Minds into every global language',
                'image' => 'assets/photos/satellite.jpg'
            ],
            'token' => [
                'title' => 'The Minds Token',
                'description' => 'Earn crypto for your contributions to the network',
                'image' => 'assets/photos/globe.jpg'
            ],
            'faq' => [
                'title' => 'FAQ',
                'description' => 'Everything you need to know about Minds',
                'image' => 'assets/photos/canyon.jpg'
            ],
            'wallet/101' => [
                'title' => 'Token 101',
                'description' => 'Everything you need to know about Minds Tokens',
                'image' => 'assets/photos/canyon.jpg',
            ],
            'wallet/tokens/101' => [
                'title' => 'Token 101',
                'description' => 'Everything you need to know about Minds Tokens',
                'image' => 'assets/photos/canyon.jpg',
            ],
        ];

        foreach ($marketing as $uri => $page) {
            Manager::add("/$uri", function ($slugs = []) use ($uri, $page) {
                $meta = [
                    'title' => $page['title'],
                    'description' => $page['description'],
                    'og:title' => $page['title'],
                    'og:description' => $page['description'],
                    'og:url' => $this->config->site_url . $uri,
                    'og:image' => $this->config->cdn_assets_url . $page['image'],
                    'og:image:width' => 2000,
                    'og:image:height' => 1000,
                    'twitter:site' => '@minds',
                    'twitter:card' => 'summary',
                ];
                return $meta;
            });
        }
    }

    public function channelHandler($slugs = []) 
    {
        $username = ($slugs[0] == 'blog') ? $slugs[1]: $slugs[0];
        if (isset($username) && is_string($username)) {
            $user = new Entities\User(strtolower($username));
            if (!$user->guid) {
                return array();
            }

            if (!$user->enabled || $user->banned == 'yes' || Helpers\Flags::shouldFail($user)) {
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
                'og:image:height' => 1000,
                'twitter:site' => '@minds',
                'twitter:card' => 'summary',
                'al:ios:url' => 'minds://channel/' . $user->guid,
                'al:android:url' => 'minds://minds/channel/' . $user->guid,
                'al:ios:app_name' => 'Minds',
                'robots' => $user->getRating() == 1 ? 'all' : 'noindex',
            ];
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
