<?php
/**
 * Network boost manager
 */
namespace Minds\Core\Boost\Network;

use Minds\Core\Di\Di;
use Minds\Core\GuidBuilder;

class Manager
{
    /** @var Repository $repository */
    private $repository;

    /** @var ElasticRepository $repository */
    private $elasticRepository;

    /** @var GuidBuilder $guidBuilder */

    public function __construct(
        $repository = null,
        $elasticRepository = null,
        $entitiesBuilder = null,
        $guidBuilder = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->elasticRepository = $elasticRepository ?: new ElasticRepository;
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->guidBuilder = $guidBuilder ?: new GuidBuilder;
    }

    /**
     * Return a list of boost
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'hydrate' => true,
            'useElastic' => false,
            'state' => null,
        ], $opts);

        if ($opts['state'] == 'review') {
            $opts['useElastic'] = true;
        }

        if ($opts['useElastic']) {
            $response = $this->elasticRepository->getList($opts);

            if ($opts['state'] === 'review') {
                $opts['guids'] = array_map(function($boost) {
                    return $boost->getGuid();
                }, $response->toArray());

                if (empty($opts['guids'])) {
                    return $response;
                }

                $response = $this->repository->getList($opts);
            }
        } else {
            $response = $this->repository->getList($opts);
        }

        if (!$opts['hydrate']) {
            return $response;
        }

        foreach ($response as $i => $boost) {
            $boost->setEntity($this->entitiesBuilder->single($boost->getEntityGuid()));
            $boost->setOwner($this->entitiesBuilder->single($boost->getOwnerGuid()));

            if (!$boost->getEntity() || !$boost->getOwner()) {
                $boost->setEntity(new \Minds\Entities\Entity());
            //    unset($response[$i]);
            }
        }
        
        return $response;
    }

    /**
     * Get a single boost
     * @param string $urn
     * @return Boost
     */
    public function get($urn, $opts = [])
    {
        $opts = array_merge([
            'hydrate' => false,
        ], $opts);

        $boost = $this->repository->get($urn);

        if ($boost && $opts['hydrate']) {
            $boost->setEntity($this->entitiesBuilder->single($boost->getEntityGuid()));
            $boost->setOwner($this->entitiesBuilder->single($boost->getOwnerGuid()));
        }

        return $boost;
    }

    /**
     * Add a boost
     * @param Boost $boost
     * @return bool
     */
    public function add($boost)
    {
        if (!$boost->getGuid()) {
            $boost->setGuid($this->guidBuilder->build());
        }
        $this->repository->add($boost);
        $this->elasticRepository->add($boost);
        return true;
    }

    public function update($boost, $fields = [])
    {
        $this->repository->update($boost, $fields);
        $this->elasticRepository->update($boost, $fields);
    }

}
