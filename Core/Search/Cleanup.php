<?php

/**
 * Cleanup Manager
 *
 * @author emi
 */

namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Di\Di;
use Minds\Entities\Entity;

class Cleanup
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    /** @var string $esIndex */
    protected $esIndex;

    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;

    /**
     * Cleanup constructor.
     * @param null $client
     * @param null $index
     * @param null $entitiesBuilder
     */
    public function __construct($client = null, $index = null, $entitiesBuilder = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->esIndex = $index ?: Di::_()->get('Config')->elasticsearch['index'];
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param Entity|string $entity
     * @return bool
     */
    public function prune($entity)
    {
        if (!$entity) {
            error_log("[Search/Index] Cannot cleanup an empty entity's index");
            return false;
        }

        if (!is_object($entity)) {
            $entity = $this->entitiesBuilder->build($entity, false);
        }

        $result = false;

        try {
            /** @var Mappings\MappingInterface $mapper */
            $mapper = Di::_()->get('Search\Mappings')->build($entity);

            $query = [
                'index' => $this->esIndex,
                'type' => $mapper->getType(),
                'id' => $mapper->getId(),
            ];

            $prepared = new Prepared\Delete();
            $prepared->query($query);
            $result = (bool) $this->client->request($prepared);
        } catch (\Exception $e) {
            error_log('[Search/Cleanup] ' . get_class($e) . ": {$e->getMessage()}");
            print_r($e);
        }

        return $result;
    }
}
