<?php

namespace Minds\Core\Analytics\UserStates;

use Minds\Core\Di\Di;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Queue;

class Manager
{
    /** @var Queue\RabbitMQ\Client */
    private $queue;

    /** @var int $referenceDate */
    private $referenceDate;

    /** @var int $rangeOffet */
    private $rangeOffset = 7;

    /** @var string $userStateIndex */
    private $userStateIndex;

    /** @var ActiveUsersIterator $activeUsersIterator */
    private $activeUsersIterator;

    /** @var UserStateIterator */
    private $userStateIterator;

    /** @var array $pendingBulkInserts **/
    private $pendingBulkInserts = [];

    public function __construct($client = null, $index = null, $queue = null, $activeUsersIterator = null, $userStateIterator = null)
    {
        $this->es = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->userStateIndex = $index ?: 'minds-kite';
        $this->queue = $queue ?: Queue\Client::build();
        $this->activeUsersIterator = $activeUsersIterator ?: new ActiveUsersIterator();
        $this->userStateIterator = $userStateIterator ?: new UserStateIterator();
    }

    public function setReferenceDate($referenceDate)
    {
        $this->referenceDate = $referenceDate;

        return $this;
    }

    public function setRangeOffset($rangeOffset)
    {
        $this->$rangeOffset = $rangeOffset;

        return $this;
    }

    public function sync()
    {
        $this->activeUsersIterator->setReferenceDate($this->referenceDate);
        $this->activeUsersIterator->setRangeOffset($this->rangeOffset);

        foreach ($this->activeUsersIterator as $activeUser) {
            $userState = (new UserState())
                ->setUserGuid($activeUser->getUserGuid())
                ->setReferenceDateMs($activeUser->getReferenceDateMs())
                ->setState($activeUser->getState())
                ->setActivityPercentage($activeUser->getActivityPercentage());
            $this->index($userState);
        }
        $this->bulk();
    }

    public function emitStateChanges()
    {
        $this->userStateIterator->setReferenceDate($this->referenceDate);

        $this->queue->setQueue('UserStateChanges');
        foreach ($this->userStateIterator as $userState) {
            //Reindex with previous state
            $this->index($userState);
            $this->queue->send([
                'user_state_change' => $userState->export(),
            ]);
        }
    }

    /**
     * Index a user user state (queues to batch)
     * @param UserState $userState
     * @return bool
     */
    public function index($userState)
    {
        $this->pendingBulkInserts[] = [ 
            'update' => [
                '_id' => "{$userState->getUserGuid()}-{$userState->getReferenceDateMs()}",
                '_index' => $this->userStateIndex,
                '_type' => 'active_user',
            ]
        ];

        $this->pendingBulkInserts[] = [ 
            'doc' => $userState->export(),
            'doc_as_upsert' => true,
        ];

        if (count($this->pendingBulkInserts) > 2000) { //1000 inserts
            $this->bulk();
        }

        return true;
    }

    /**
     * Run a bulk insert job (quicker)
     * @return void
     */
    public function bulk()
    {
        $this->es->bulk([ 'body' => $this->pendingBulkInserts ]);
        $this->pendingBulkInserts = [];
    }
}
