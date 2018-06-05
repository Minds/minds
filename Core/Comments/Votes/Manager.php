<?php

/**
 * Minds Comments Votes Manager
 *
 * @author emi
 */

namespace Minds\Core\Comments\Votes;

use Minds\Core\Comments\Legacy\Repository as LegacyCommentsRepository;
use Minds\Core\Votes\Vote;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var LegacyCommentsRepository */
    protected $legacyRepository;

    /** @var Vote */
    protected $vote;

    /**
     * Manager constructor.
     * @param null $repository
     */
    public function __construct($repository = null, $legacyRepository = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->legacyRepository = $legacyRepository ?: new LegacyCommentsRepository();
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
        if ($this->legacyRepository->isLegacy($this->vote->getEntity()->guid)) {
            return null;
        }

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
        $done = $this->repository->add($this->vote);

        if ($this->legacyRepository->isLegacy($this->vote->getEntity()->guid)) {
            return null;
        }

        return $done;
    }

    /**
     * Cancels a vote on a comment
     * @return bool
     */
    public function cancel()
    {
        $done = $this->repository->delete($this->vote);

        if ($this->legacyRepository->isLegacy($this->vote->getEntity()->guid)) {
            return null;
        }

        return $done;
    }
}
