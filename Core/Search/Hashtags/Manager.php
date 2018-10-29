<?php

namespace Minds\Core\Search\Hashtags;


use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Data\ElasticSearch\Prepared;
use Minds\Core\Di\Di;
use Minds\Core\Search\Search;

class Manager
{
    /** @var Client */
    private $client;
    /** @var Search */
    private $search;
    /** @var string */
    private $hashtagsIndex;

    /** @var int */
    private $limit;

    public function __construct($client = null, $search = null, $index = null)
    {
        $this->client = $client ?: Di::_()->get('Database\ElasticSearch');
        $this->search = $search ?: Di::_()->get('Search\Search');

        $this->hashtagsIndex = $index ?: 'minds-trending-hashtags';
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setLimit(int $value)
    {
        $this->limit = $value;
        return $this;
    }

    public function suggest($value)
    {
        $result = $this->search->suggest('tags', $value, $this->limit);
        return array_map(function($item) {
            return $item['name'];
        }, $result);
    }

    public function index($hashtag)
    {
        $body = [
            "script" => [
                "source" => "ctx._source.count += params.count; ctx._source.suggest.weight += params.count",
                "lang" => "painless",
                "params" => [
                    "count" => 1,
                ]
            ],
            "upsert" => [
                "name" => $hashtag,
                "count" => 1,
                "suggest" => [
                    'input' => [$hashtag],
                    'weight' => 1
                ]
            ]
        ];

        $query = [
            'index' => $this->hashtagsIndex,
            'type' => 'tags',
            'id' => md5($hashtag),
            'body' => $body
        ];

        $prepared = new Prepared\Update();
        $prepared->query($query);

        try {
            $result = (bool) $this->client->request($prepared);
        } catch (\Exception $e) {
            error_log($e);
            return false;
        }

        return $result;
    }
}