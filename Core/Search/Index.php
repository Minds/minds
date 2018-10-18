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
use Minds\Entities\Entity;
use Minds\Exceptions\BannedException;

class Index
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    /** @var string $esIndex */
    protected $esIndex;

    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Core\Search\Hashtags\Manager */
    protected $hashtagsManager;

    /**
     * Index constructor.
     * @param null $client
     * @param null $index
     * @param null $entitiesBuilder
     * @param null $hashtagsManager
     */
    public function __construct($client = null, $index = null, $entitiesBuilder = null, $hashtagsManager = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->esIndex = $index ?: Di::_()->get('Config')->elasticsearch['index'];
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->hashtagsManager = $hashtagsManager ?: Di::_()->get('Search\Hashtags\Manager');
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
            $entity = $this->entitiesBuilder->build($entity, false);
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

            // if hashtags were found, index them separately
            if(in_array('tags', $body)) {
                foreach($body['tags'] as $tag) {
                    $this->hashtagsManager->index($tag);
                }
            }
        } catch (BannedException $e) {
            $result = false;
        } catch (\Exception $e) {
            error_log('[Search/Index] ' . get_class($e) . ": {$e->getMessage()}");
            $result = false;
        }

        return $result;
    }

    /**
     * @param Entity|string $entity
     * @param array $opts
     * @return bool
     */
    public function update($entity, $opts)
    {
        if (!$entity) {
            error_log("[Search/Index] Cannot update an empty entity's index");
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
                'body' => ['doc' => $opts]
            ];

            $prepared = new Prepared\Update();
            $prepared->query($query);
            $result = (bool) $this->client->request($prepared);
        } catch (\Exception $e) {
            error_log('[Search/Index] ' . get_class($e) . ": {$e->getMessage()}");
            print_r($e);
        }

        return $result;
    }
}
