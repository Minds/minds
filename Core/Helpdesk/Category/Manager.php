<?php

namespace Minds\Core\Helpdesk\Category;


use Minds\Core\Di\Di;
use Minds\Core\Helpdesk\Entities\Category;

class Manager
{
    /** @var Repository */
    private $repo;

    public function __construct($repository = null)
    {
        $this->repo = $repository ?: Di::_()->get('Helpdesk\Category\Repository');
    }

    /**
     * @param array $opts
     * @return Category[]
     */
    public function getAll(array $opts = [])
    {
        $opts = array_merge([
            'limit' => 10,
            'offset' => 0,
            'uuid' => '',
            'recursive' => false,
        ], $opts);
        return $this->repo->getAll($opts);
    }

    public function getOne($uuid)
    {
        return $this->repo->getOne($uuid);
    }

    public function getBranch($uuid)
    {
        return $this->repo->getBranch($uuid);
    }

    public function add(Category $category)
    {
        return $this->repo->add($category);
    }

    public function delete(string $category_uuid)
    {
        return $this->repo->delete($category_uuid);
    }
}