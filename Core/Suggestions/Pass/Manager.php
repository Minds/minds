<?php
/**
 * Pass a suggestion to not see it again
 */
namespace Minds\Core\Suggestions\Pass;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository();
    }

    /**
     * Add a pass to the datastore
     * @param Pass $pass
     * @return boolean
     */
    public function add($pass)
    {
        return $this->repository->add($pass);
    }
}

