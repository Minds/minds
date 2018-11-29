<?php
/**
 * Helpdesk votes manager
 */
namespace Minds\Core\Helpdesk\Question\Votes;


use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Question;

class Manager
{
    /** @var Repository */
    private $repository;

    /** @var Question */
    private $question;

    /** @var User */
    private $user;

    /** @var string */
    private $direction;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
    }

    /**
     * @param Question $question
     * @return Manager
     */
    public function setQuestion($question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * Set user
     * @param User $user
     * @return Manager
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * Case a vote
     * @return bool
     */
    public function vote()
    {
        $vote = new Vote();
        $vote
            ->setUserGuid($this->user->getGuid())
            ->setQuestionUuid($this->question->getUuid())
            ->setDirection($this->direction);

        return $this->repository->add($vote);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $vote = new Vote();
        $vote
            ->setUserGuid($this->user->getGuid())
            ->setQuestionUuid($this->question->getUuid())
            ->setDirection($this->direction);

        return $this->repository->delete($vote);
    }
}