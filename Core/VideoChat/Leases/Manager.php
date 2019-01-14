<?php
/**
 * VideoChatLeases Manager
 */
namespace Minds\Core\VideoChat\Leases;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
    }

    /**
     * Add a lease
     * @param VideoChatLease
     * @return bool
     */
    public function add($lease)
    {
        return $this->repository->add($lease);
    }

    /**
     * Get a lease
     * @param string key
     */
    public function get($key)
    {
        return $this->repository->get($key);
    }

}