<?php

/**
 * Minds Search normal search
 *
 * @author emi
 */

namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Di\Di;

class Search
{
    /** @var Core\Data\ElasticSearch\Client $client */
    protected $client;

    /** @var string $esIndex */
    protected $esIndex;

    /** @var array $allowedTypes */
    protected $allowedTypes = [
        'activity',
        'user',
        'group',
        'object:blog',
        'object:image',
        'object:album',
        'object:video',
    ];

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
     * Queries the search storage
     * @param array $options
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws \Exception
     */
    public function query(array $options, $limit = 12, $offset = 0)
    {
        $prepared = new Prepared\Match();
        $prepared->setIndex($this->esIndex);

        $options = array_merge([
            'text' => '',
            'taxonomies' => null,
            'container' => null,
            'mature' => false,
            'paywall' => null,
            'license' => null,
            'sort' => null
        ], $options);

        // Initial parameters

        $match = [];
        $filters = [];
        $params = [];

        // Limit and offset

        if ($limit) {
            $params['size'] = $limit;
        }

        if ($offset) {
            $params['from'] = $offset;
        }

        // Text Query

        $match['query'] = preg_replace('/[^A-Za-z0-9_\-#"+]/', ' ', $options['text']);

        // Check taxonomies

        if ($options['taxonomies']) {
            if ($options['taxonomies'] && !is_array($options['taxonomies'])) {
                $options['taxonomies'] = [ $options['taxonomies'] ];
            }

            foreach ($options['taxonomies'] as $taxonomy) {
                if (!in_array($taxonomy, $this->allowedTypes)) {
                    throw new \Exception('Unknown taxonomy: ' . $taxonomy);
                }
            }

            $filters['taxonomy'] = $options['taxonomies'];
        }

        // Container

        if ($options['container']) {
            $filters['container_guid'] = $options['container'];
        } else {
            $filters['public'] = true;
        }

        // Mature

        if ($options['mature'] !== null) {
            $filters['mature'] = !!$options['mature'];
        }

        // Exclusive

        if ($options['paywall'] !== null) {
            $filters['paywall'] = !!$options['paywall'];
        }

        // Mature

        if ($options['license'] !== null) {
            $filters['license'] = $options['license'];
        }

        // Sorting

        if ($options['sort'] == 'latest') {
            $match['fields'] = [ '_all' ];

            $params['sort'] = [
                [ '@timestamp' => 'desc' ]
            ];
        } elseif ($options['sort'] == 'top') {
            $match['fields'] = [ 'name^6', 'title^8', 'message^8', 'username^8', 'tags^64'  ];
            $prepared->setRange([
                [ 
                    'interactions' => [
                        'gt' => '0'
                        ]
                ],
                [
                    '@timestamp' => [
                        'gte' => strtotime('48 hours ago') * 1000
                    ]
                ]
            ]);
            //prevent people gaming the hashtags
            $prepared->setScripts([ "doc['tags.keyword'].values.size() < 3" ]); 
            $params['field_value_factor'] = [
                'field' => 'interactions',
                'modifier' => 'log1p',
                'factor' => 2,
                'missing' => 0.1
            ];
            $params['sort'] = [
                [ 'interactions' => 'desc' ]
            ]; 
        }

        // Execute
        $guids = [];

        $prepared->query($this->esIndex, $match, $filters, $params);
        $results = $this->client->request($prepared);

        foreach ($results['hits']['hits'] as $result) {
            $guids[] = $result['_id'];
        }

        return $guids;
    }

    public function suggest($taxonomy, $query, $limit = 12)
    {
        $params = [
            'size' => $limit
        ];

        // TODO: implement $taxonomy

        $prepared = new Prepared\Suggest();
        $prepared->query($this->esIndex, $query, $params);

        $results = $this->client->request($prepared);

        if (!isset($results['suggest']['autocomplete'][0]['options'])) {
            return [];
        }

        $entities = array_map(function ($document) {
            return $document['_source'];
        }, $results['suggest']['autocomplete'][0]['options']);

        return $entities;
    }
}
