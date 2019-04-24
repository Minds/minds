<?php
/**
 * Helpdesk votes manager
 */

namespace Minds\Core\Helpdesk\Question\Votes;

use Minds\Core\Helpdesk\Question\Question;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Entities\User;

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
        $key = $this->direction === 'up' ? 'ThumbsUp' : 'ThumbsDown';
        $getter = 'get' . $key;
        $setter = 'set' . $key;

        $array = $this->question->$getter();

        if (!in_array($this->user->getGuid(), $array)) {
            $array[] = (string) $this->user->getGuid();
            $this->question->$setter($array);
        }

        return $this->repository->update($this->question);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $key = $this->direction === 'up' ? 'ThumbsUp' : 'ThumbsDown';
        $getter = 'get' . $key;
        $setter = 'set' . $key;

        $array = $this->question->$getter();

        $index = array_search($this->user->getGuid(), $array);
        if ($index !== -1) {
            array_splice($array, $index, 1);
            $this->question->$setter($array);
        }

        return $this->repository->update($this->question);
    }
}
