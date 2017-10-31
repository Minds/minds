<?php

/**
 * Minds Search Provisioner
 *
 * @author emi
 */

namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Config;
use Minds\Core\Di\Di;

class Provisioner
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    /** @var string $esIndex */
    protected $esIndex;

    protected $mappings = [
        'activity' => Core\Search\Mappings\ActivityMapping::class,
        'group' => Core\Search\Mappings\GroupMapping::class,
        'object:blog' => Core\Search\Mappings\ObjectBlogMapping::class,
        'object:image' => Core\Search\Mappings\ObjectImageMapping::class,
        'object:video' => Core\Search\Mappings\ObjectVideoMapping::class,
        'user' => Core\Search\Mappings\UserMapping::class
    ];

    public function __construct($client = null, $esIndex = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->esIndex = $esIndex ?: Di::_()->get('Config')->elasticsearch['index'];
    }

    public function setUp()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        foreach ($this->mappings as $type => $mapping) {
            /** @var Core\Search\Mappings\MappingInterface $mappingClass */
            $mappingClass = new $mapping();

            $mappings = $mappingClass->getMappings();

            array_walk($mappings, function (&$mapping) {
                if (!is_array($mapping)) {
                    return;
                }

                $mapping = array_filter($mapping, function ($key) {
                    return strpos($key, '$') !== 0;
                }, ARRAY_FILTER_USE_KEY);
            });

            $this->client
                ->getClient()
                ->indices()
                ->putMapping([
                    'index' => $this->esIndex,
                    'type' => $type,
                    'body' => [
                        $type => [
                            'properties' => $mappings
                        ]
                    ]
                ]);
        }
    }
}
