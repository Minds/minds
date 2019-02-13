<?php

namespace Minds\Core\Subscriptions;

use Cassandra;
use Minds\Common\Repository\Response;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Di\Di;
use Minds\Core\Util\UUIDGenerator;

class Repository
{
    /** @var Client */
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'uuid' => '',
            'recursive' => false,
        ], $opts);

    }

    /**
     */
    public function get($uuid)
    {
    }

    /**
     * Add a subscription
     *
     * @param Category $category
     * @return string|false
     */
    public function add(Subscription $subscription)
    {
        // Do a batch request for consistency
        $requests = [];
            
        // Write to friends table
        $requests[] = [
            'string' => "INSERT INTO friends (key, column1, value) VALUES (?, ?, ?)",
            'values' => [
                (string) $subscription->getSubscriberGuid(),
                (string) $subscription->getPublisherGuid(),
                (string) time(),
            ],
        ];

        // Write to friends_of table
        $requests[] = [
            'string' => "INSERT INTO friendsof (key, column1, value) VALUES (?, ?, ?)",
            'values' => [
                (string) $subscription->getPublisherGuid(),
                (string) $subscription->getSubscriberGuid(),
                (string) time(),    
            ],  
        ];
        
        // Send request
        if (!$this->client->batchRequest($requests, Cassandra::BATCH_UNLOGGED)) {
            return false;
        };

        $subscription->setActive(true);

        return $subscription;
    }

    /**
     * Delete a subscripiption
     *
     * @param Subscription $subscription 
     * @return bool
     */
    public function delete(Subscription $subscription)
    {
        // Do a batch request for consistency
        $requests = [];

        // Write to friends table
        $requests[] = [
            'string' => "DELETE FROM friends WHERE key=? AND column1=?",
            'values' => [
                (string) $subscription->getSubscriberGuid(),
                (string) $subscription->getPublisherGuid(),
            ],
        ];

        // Write to friends_of table
        $requests[] = [
            'string' => "DELETE FROM friendsof WHERE key=? AND column1=?",
            'values' => [
                (string) $subscription->getPublisherGuid(),
                (string) $subscription->getSubscriberGuid(),
            ],
        ];

        // Send request
        if (!$this->client->batchRequest($requests, Cassandra::BATCH_UNLOGGED)) {
            return false;
        };

        $subscription->setActive(false);

        return $subscription;
    }

}

