<?php

namespace Minds\Core\SendWyre;

use Minds\Core\Di\Di;

class Manager
{
    /** @var Repository */
    protected $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('SendWyre\Repository');
    }

    public function get($userGuid)
    {
        return $this->repository->get($userGuid);
    }

    public function save($sendWyreAccount)
    {
        return $this->repository->save($sendWyreAccount);
    }

    public function delete($sendWyreAccount)
    {
        return $this->repository->delete($sendWyreAccount);
    }
}
