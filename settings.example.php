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
    // Are we on the testnet?
    'testnet' => true,

    // Our network address
    'network_address' => 'http://localhost:9545',

    // Endpoints to connect to the blockchain
    'rpc_endpoints' => [ 'http://10.0.2.2:8545' ],

    // Client network (1 = main eth network; 4 = rinkeby, 1337 Minds/coin repo's testserver.sh)
    'client_network' => 1,

    // Escrow wallet
    'wallet_address' => '0x0000000000000000000000000000000000000000',

    // Escrow wallet private key
    'wallet_pkey' => '0x0000000000000000000000000000000000000000000000000000000000000000',

    // Incentive funds wallet
    'incentive_wallet_address' => '0x0000000000000000000000000000000000000000',

    // Incentive funds wallet private key
    'incentive_wallet_pkey' => '0x0000000000000000000000000000000000000000000000000000000000000000',

    // Boost escrow wallet
    'boost_wallet_address' => '0x0000000000000000000000000000000000000000',

    // Boost escrow wallet private key
    'boost_wallet_pkey' => '0x0000000000000000000000000000000000000000000000000000000000000000',

    // Default gas price in Gwei
    'default_gas_price' => 1,

    // Default gas price in Gwei used for server-side transactions
    'server_gas_price' => 1,

    // Token
    'token_address' => '0x0000000000000000000000000000000000000000',

    // Wire
    'wire_address' => '0x0000000000000000000000000000000000000000',

    // Peer Boost
    'peer_boost_address' => '0x0000000000000000000000000000000000000000',

    // Token Distribution Event
    'token_distribution_event_address' => '0x0000000000000000000000000000000000000000',

    // Web3 Interface binary
    'mw3' => '/usr/bin/env node /path/to/mw3/index.js',

    'token_name' => '',

    'token_symbol' => '',

    'disable_creditcards' => false,

    'offchain' => [
        'cap' => 10,
        'withholding' => [
            'wire' => 30 * 24 * 60 * 60,
            'boost' => 30 * 24 * 60 * 60,
        ]
    ],
    'sale' => false, // false | 'presale' | 'sale'

    'max_pledge_amount' => 1800, // 1800 ETH
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
