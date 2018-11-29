<?php

namespace Minds\Core\Helpdesk\Question;


use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Question;

class Manager
{
    /** @var Repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Helpdesk\Question\Repository');
    }

    /**
     * @param array $opts
     * @return Question[]
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'category_uuid' => null,
            'question_uuid' => null,
            'user_guid' => null, // for thumbs
            'orderBy' => null, // has to be a valid field
            'orderDirection' => 'DESC',
            'hydrateCategory' => false,
        ], $opts);
        return $this->repository->getList($opts);
    }

    public function getTop(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 8,
        ], $opts);
        return $this->repository->top($opts);
    }

    public function suggest(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'q' => ''
        ], $opts);
        return $this->repository->suggest($opts);
    }

    public function add(Question $entity)
    {
        return $this->repository->add($entity);
    }

    public function update(string $question_uuid, array $fields)
    {
        return $this->repository->update($question_uuid, $fields);
    }

    public function vote($uuid, $direction)
    {
        return $this->repository->vote($uuid, $direction);
    }

    public function unvote($uuid)
    {
        return $this->repository->unvote($uuid);
    }

    public function getVote($uuid, $userGuid)
    {
        return $this->repository->getVote($uuid, $userGuid);
    }

    public function delete(string $question_uuid)
    {
        return $this->repository->delete($question_uuid);
    }
}