<?php
$CONFIG = Minds\Core\Di\Di::_()->get('Config');

$CONFIG->minds_debug = false;

/*
 * Cassandra configuration
 */
$CONFIG->cassandra = (object) [
    'keyspace'    => '{{cassandra-keyspace}}',
    'servers'     => [ '{{cassandra-server}}' ],
    'cql_servers' => [ '{{cassandra-server}}' ]
];

$CONFIG->redis = [
    'master' => 'redis',
    'slave' => 'redis'
];

/**
 * Other Elgg Settings
 */
$CONFIG->installed = true;
$CONFIG->path = '{{path}}';
$CONFIG->plugins_path = '{{path}}plugins/';
$CONFIG->pluginspath = '{{path}}plugins/';
$CONFIG->dataroot = '{{dataroot}}';
$CONFIG->default_site = '{{default-site}}';
$CONFIG->site_id = '{{default-site}}';
$CONFIG->site_name = '{{site-name}}';
$CONFIG->__site_secret__ = '{{site-secret}}';
// $CONFIG->cdn_url = 'http://{{domain}}/';
$CONFIG->site_url = 'http://{{domain}}/';
$CONFIG->zmq_server = 'localhost';

/**
 * Overrides default system cache path from inside data root to custom location.
 *
 * @global string $CONFIG->system_cache_path
 * @name $CONFIG->system_cache_path
 */
$CONFIG->system_cache_path = '{{cache-path}}';

/**
 * Elasticsearch Settings
 */
//server for elasticsearch
$CONFIG->elasticsearch_server = '{{elasticsearch-server}}';
//namespace
$CONFIG->elasticsearch_prefix = '{{elasticsearch-prefix}}';

/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://uk.php.net/manual/en/memcache.setup.php)
 *
 * Note: Multiple server support is only available on server 1.2.1
 * or higher with PECL library > 2.0.0
 */

 /*$CONFIG->memcache = true;

$CONFIG->memcache_servers = array (
    array('server1', 11211),
    array('server2', 11211)
);*/

/**
 * Queue Settings
 */
$CONFIG->queue = [
    'exchange' => '{{ queue-exchange }}'
];

/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = false;

/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;

$CONFIG->set('plugins', [
  'Messenger',
  'oauth2',
  'guard',
]);

$CONFIG->set('sockets-jwt-secret', '{{jwt-secret}}');
$CONFIG->set('sockets-jwt-domain', '{{jwt-domain}}');
$CONFIG->set('sockets-server-uri', '{{socket-server-uri}}');

$CONFIG->set('facebook', [
    'app_id' => '{{facebook-app-id}}',
    'app_secret' => '{{facebook-app-secret}}'
]);

$CONFIG->set('twitter', [
    'api_key' => '{{twitter-app-id}}',
    'api_secret' => '{{twitter-app-id}}'
]);

$CONFIG->set('twilio', [
    'account_sid' => '{{twilio-account-sid}}',
    'auth_token' => '{{twilio-auth-token}}',
    'from' => '{{twilio-from}}'
]);

$CONFIG->set('google', [
    'geolocation' => '{{google-api-key}}',
    'translation' => '{{google-api-key}}',
    'push' => '{{google-api-key}}',
    'analytics' => [
        'service_account' => [
            'key_path' => __DIR__ . '/.auth/analytics.json',
        ],
        'ads' => '', // get it from https://ga-dev-tools.appspot.com/account-explorer/
    ]
]);

$CONFIG->set('apple', [
    'sandbox' => '{{apple-sandbox-enabled}}',
    'cert' => '{{apple-certificate}}'
]);

$CONFIG->set('boost', [
    'network' => [
        'min' => 100,
        'max' => 5000,
    ],
    'peer' => [
        'min' => 100,
        'max' => 5000000
    ],
]);

$CONFIG->set('encryptionKeys', [
    'email' => [
        'private' => '{{email-private-key}}',
        'public' => '{{email-public-key}}'
    ],
    'phone_number' => [
        'private' => '{{phone-number-private-key}}',
        'public' => '{{phone-number-public-key}}'
    ]
]);

$CONFIG->set('payouts', [
    'initialDate' => '2016-11-01',
    'retentionDays' => 40,
    'minimumAmount' => 100,
    'userPercentage' => 0.8
]);

$CONFIG->set('payments', [
    'stripe' => [
        'api_key' => '',
        'transfers' => [
            'source_type' => 'bank_account'
        ]
    ]
]);

$CONFIG->set('sandbox', [
    'enabled' => false,
    'default' => [
        'guid' => '',
    ],
    'merchant' => [
        'guid' => '',
    ],
]);

$CONFIG->set('sns_secret', '{{sns-secret}}');

$CONFIG->set('blockchain', [
    'sale' => 'sale',
    'testnet' => false,

    'rpc_endpoints' => [
        'https://mainnet.infura.io/v3/708b51690a43476092936f9818f8c4fa',
    ],
    
    //'network_address' => 'https://rinkeby.infura.io/',
    'proxy_rpc_endpoint' => 'https://mainnet.infura.io/v3/708b51690a43476092936f9818f8c4fa',

    'client_network' => 1, // 1 = main ethereum network; 4 = test rinkeby; 1337 coin repo's testserver.sh

    'default_gas_price' => 40,
    'server_gas_price' => 40,
    'token_symbol' => 'status',

    'token_address' => '0xb26631c6dda06ad89b93c71400d25692de89c068',
    'contracts' => [
        'token_sale_event' => [
            'contract_address' => '0xf3c9dbb9598c21fe64a67d0586adb5d6eb66bc63',
            'wallet_address' => '0x1820fFAD63fD64d7077Da4355e9641dfFf4DAD0d',
            'wallet_pkey' => '',
            'eth_rate' => 2000, //1 ETH = 2,000 TOKENS
            'auto_issue_cap' => "120000000000000000000000", //60ETH (120,000 tokens) $30,000 USD
        ],
        'withdraw' => [
            'contract_address' => '0xdd10ccb3100980ecfdcbb1175033f0c8fa40548c',
            'wallet_address' => '0x14E421986C5ff2951979987Cdd82Fa3C0637D569',
            'wallet_pkey' => '',
            'limit_exemptions' => [
            ],
        ],
        'bonus' => [
            'wallet_address' => '0x461f1C5768cDB7E567A84E22b19db0eABa069BaD',
            'wallet_pkey' => '',
        ],
        'boost' => [
            'contract_address' => '0x112ca67c8e9a6ac65e1a2753613d37b89ab7436b',
            'wallet_address' => '0xdd04D9636F1944FE24f1b4E51Ba77a6CD23b6fE3',
            'wallet_pkey' => '',
        ],
        'wire' => [
            'contract_address' => '0x4b637bba81d24657d4c6acc173275f3e11a8d5d7',
            'wallet_address' => '0x4CDc1C1fd1A3F4DD63231afF8c16501BcC11Df95',
            'wallet_pkey' => '',
        ],
     ],

    'eth_rate' => 2000, //1 ETH = 2,000 TOKENS

    'disable_creditcards' => true,

    'offchain' => [
        'cap' => 1000
    ],

    'mw3' => '/usr/bin/env node ' . __MINDS_ROOT__ . '/../mw3/index.js'
]);

$CONFIG->set('blockchain_override', [
    'pledge' => [
        // ...
    ],
]);

$CONFIG->set('plus', [
    'tokens' => [
        'month' => 5,
        'year' => 50
    ]
]);

$CONFIG->set('iframely' , [
    'key' => 'f4da1791510e9dd6ad63bc',
    'origin' => 'minds'
]);

$CONFIG->set('default_email_subscriptions', [
    [
        'campaign' => 'when',
        'topic' => 'unread_notifications',
        'value' => true
    ],
    [
        'campaign' => 'when',
        'topic' => 'wire_received',
        'value' => true
    ],
    [
        'campaign' => 'when',
        'topic' => 'boost_completed',
        'value' => true
    ],

    [
        'campaign' => 'with',
        'topic' => 'top_posts',
        'value' => 'periodically'
    ],
    [
        'campaign' => 'with',
        'topic' => 'channel_improvement_tips',
        'value' => true
    ],
    [
        'campaign' => 'with',
        'topic' => 'posts_missed_since_login',
        'value' => true
    ],
    [
        'campaign' => 'with',
        'topic' => 'new_channels',
        'value' => true
    ],

    [
        'campaign' => 'global',
        'topic' => 'minds_news',
        'value' => false
    ],
    [
        'campaign' => 'global',
        'topic' => 'minds_tips',
        'value' => true
    ],
    [
        'campaign' => 'global',
        'topic' => 'exclusive_promotions',
        'value' => false
    ],
]);


$CONFIG->set('i18n', [
    'languages' => [
        'en' => 'English',
        'es' => 'Español',
    ]
]);

// blacklist of internal IPs / URLs to block from curl requests
$CONFIG->set('internal_blacklist', []);
