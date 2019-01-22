<?php
/**
 * SpamBlocks Manager
 */
namespace Minds\Core\Security\SpamBlocks;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
    }

    /**
     * Return if a spamblock exists
     * @param SpamBlock $model
     * @return bool
     */
    public function isSpam($model)
    {
        return (bool) $this->repository->get($model->getKey(), $model->getValue());
    }

    /**
     * Create a new spam block
     * @param SpamBlock $model
     * @return bool
     */
    public function add($model)
    {
        return $this->repository->add($model);
    }

}