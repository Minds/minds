<?php

/**
 * Minds Comments Votes Manager
 *
 * @author emi
 */

namespace Minds\Core\Comments\Votes;

use Minds\Core\Votes\Vote;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var Vote */
    protected $vote;

    /**
     * Manager constructor.
     * @param null $repository
     */
    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository();
    }

    /**
     * @param Vote $vote
     * @return Manager
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
        return $this;
    }

    /**
     * Checks if a vote was casted in a comment
     * @return bool
     */
    public function has()
    {
        $votes = [];

        switch ($this->vote->getDirection()) {
            case 'up':
                $votes = $this->vote->getEntity()->getVotesUp();
                break;

            case 'down':
                $votes = $this->vote->getEntity()->getVotesDown();
                break;
        }

        return in_array($this->vote->getActor()->guid, $votes);
    }

    /**
     * Casts a vote on a comment
     * @return bool
     */
    public function cast()
    {
        return $this->repository->add($this->vote);
    }

    /**
     * Cancels a vote on a comment
     * @return bool
     */
    public function cancel()
    {
        return $this->repository->delete($this->vote);
    }
}