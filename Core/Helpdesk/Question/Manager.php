<?php

namespace Minds\Core\Helpdesk\Question;


use Minds\Common\Repository\Response;
use Minds\Core\Di\Di;

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
     * @return Response
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => '',
            'category_uuid' => null,
            'question_uuid' => null,
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

    public function get($uuid)
    {
        return $this->repository->get($uuid);
    }

    public function add(Question $entity)
    {
        return $this->repository->add($entity);
    }

    public function update(Question $entity)
    {
        return $this->repository->update($entity);
    }

    public function delete($uuid)
    {
        return $this->repository->delete($uuid);
    }

}
