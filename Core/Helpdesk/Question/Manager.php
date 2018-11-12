<?php

namespace Minds\Core\Helpdesk\Question;


use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Question;

class Manager
{
    /** @var Repository */
    private $repo;

    public function __construct($repository = null)
    {
        $this->repo = $repository ?: Di::_()->get('Helpdesk\Question\Repository');
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
        return $this->repo->getAll($opts);
    }

    public function getTop(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 8,
        ], $opts);
        return $this->repo->top($opts);
    }

    public function suggest(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'q' => ''
        ], $opts);
        return $this->repo->suggest($opts);
    }

    public function add(Question $entity)
    {
        return $this->repo->add($entity);
    }

    public function update(string $question_uuid, array $fields)
    {
        return $this->repo->update($question_uuid, $fields);
    }

    public function vote($uuid, $direction)
    {
        return $this->repo->vote($uuid, $direction);
    }

    public function unvote($uuid)
    {
        return $this->repo->unvote($uuid);
    }

    public function getVote($uuid, $userGuid)
    {
        return $this->repo->getVote($uuid, $userGuid);
    }

    public function delete(string $question_uuid)
    {
        return $this->repo->delete($question_uuid);
    }
}