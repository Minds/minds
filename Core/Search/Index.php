<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Di\Di;
use Minds\Exceptions\BannedException;

class Index
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    /** @var string $esIndex */
    protected $esIndex;

    /**
     * Index constructor.
     * @param null $client
     * @param null $index
     */
    public function __construct($client = null, $index = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->esIndex = $index ?: Di::_()->get('Config')->elasticsearch['index'];
    }

    /**
     * Indexes an entity
     * @param $entity
     * @return bool
     */
    public function index($entity)
    {
        if (!$entity) {
            error_log('[Search/Index] Cannot index an empty entity');
            return false;
        }

        if (!is_object($entity)) {
            $entity = Core\Entities::build($entity, false);
        }

        try {
            /** @var Mappings\MappingInterface $mapper */
            $mapper = Di::_()->get('Search\Mappings')->build($entity);

            $body = $mapper->map();

            if ($suggest = $mapper->suggestMap()) {
                $body = array_merge($body, [
                    'suggest' => $suggest
                ]);
            }

            $query = [
                'index' => $this->esIndex,
                'type' => $mapper->getType(),
                'id' => $mapper->getId(),
                'body' => $body
            ];

            $prepared = new Prepared\Index();
            $prepared->query($query);

            $result = (bool) $this->client->request($prepared);
        } catch (BannedException $e) {
            $result = false;
        } catch (\Exception $e) {
            error_log('[Search/Index] ' . get_class($e) . ": {$e->getMessage()}");
            $result = false;
        }

        return $result;
    }
}
