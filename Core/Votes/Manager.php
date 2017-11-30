<?php

/**
 * Votes Manager
 *
 * @author emi
 */

namespace Minds\Core\Votes;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Security\ACL;
use Minds\Entities\Factory;

class Manager
{

    protected $counters;
    protected $indexes;

    protected $entity;
    protected $actor;

    protected $acl;

    /**
     * Manager constructor.
     */
    public function __construct($counters = null, $indexes = null, $acl = null)
    {
        $this->counters = $counters ?:  Di::_()->get('Votes\Counters');
        $this->indexes =  $indexes ?: Di::_()->get('Votes\Indexes');
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * Casts a vote
     * @param string $direction
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function cast($vote, array $options = [])
    {
        $options = array_merge([
            'events' => true
        ], $options);

        if (!$this->acl->interact($vote->getEntity(), $vote->getActor())) {
            throw new \Exception('Actor cannot interact with entity');
        }
        
        //update counts
        $this->counters->update($vote);

        //update indexes
        $done = $this->indexes->insert($vote);

        if ($done && $options['events']) {
            Dispatcher::trigger('vote', $vote->getDirection(), [
                'vote' => $vote
            ]);
        }

        return $done;
    }

    /**
     * Cancels a vote
     * @param string $direction
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function cancel($vote, array $options = [])
    {
        $options = array_merge([
            'events' => true
        ], $options);

        //update counts
        $this->counters->update($vote, -1);

        //update indexes
        $done = $this->indexes->remove($vote);

        if ($done && $options['events']) {
            Dispatcher::trigger('vote:cancel', $vote->getDirection(), [
                'vote' => $vote
            ]);
        }

        return $done;
    }

    /**
     * Returns a boolean stating if actor voted on the entity
     * @param string $direction
     * @return bool
     * @throws \Exception
     */
    public function has($vote)
    {
        return $this->indexes->exists($vote);
    }

    /**
     * Toggles a vote (cancels if exists, votes if doesn't) [wrapper]
     * @param string $direction
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function toggle($vote, array $options = [])
    {
        $options = array_merge([
            'events' => true
        ], $options);

        if (!$this->has($vote)) {
            return $this->cast($vote, $options);
        } else {
            return $this->cancel($vote, $options);
        }
    }

}
