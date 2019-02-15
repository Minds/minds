<?php
namespace Minds\Core\Suggestions;

use Minds\Core\EntitiesBuilder;

class Manager
{

    /** @var $repository */
    private $repository;

    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var User $user */
    private $user;

    /** @var string $type */
    private $type;

    public function __construct($repository = null, $entitiesBuilder = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder();
    }

    /**
     * Set the user to return data for
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the type to return data for
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Return a list of users
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'limit' => 12,
            'paging-token' => '',
        ], $opts);

        $opts['user_guid'] = $this->user->getGuid();

        $response = $this->repository->getList($opts);
        
        // Hydrate the entities
        // TODO: make this a bulk request vs sequential
        foreach ($response as $suggestion) {
            $entity = $this->entitiesBuilder->single($suggestion->getEntityGuid());
            $suggestion->setEntity($entity);
        }
        return $response;
    }

}
