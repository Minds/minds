<?php
/**
 * Hashtags / Tag Cloud
 */
namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Di\Di;

class Tagcloud
{
    const LIMIT = 10;
    const CACHE_DURATION = 60 * 60;

    const CACHE_KEY = 'trending:hashtags';
    const TIME_CACHE_KEY = 'trending:hashtags:time';
    const HIDDEN_DB_KEY = 'trending:hashtags:hidden';

    protected $db;
    protected $cache;

    public function __construct($db = null, $cache = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Indexes');
        $this->cache = $cache ?: Di::_()->get('Cache\Apcu');
    }

    public function get()
    {
        if ($cached = $this->cache->get(static::CACHE_KEY)) {
            $result = json_decode($cached, true);
        } else {
            $hidden = array_keys($this->fetchHidden(5000)); // @todo: last 5000?
            $tags = $this->fetch(static::LIMIT * 3);

            $result = array_slice(self::fast_array_diff($tags, $hidden), 0, static::LIMIT, false);

            $this->cache->set(static::TIME_CACHE_KEY, time(), static::CACHE_DURATION);
            $this->cache->set(static::CACHE_KEY, json_encode($result), static::CACHE_DURATION);
        }

        return $result;
    }

    public function getAge()
    {
        $time = $this->cache->get(static::TIME_CACHE_KEY);

        if (!$time) {
            return false;
        }

        return time() - $time;
    }

    public function hide($tag)
    {
        return (bool) $this->db->insert(static::HIDDEN_DB_KEY, [ $tag => time() ]);
    }

    public function unhide($tag)
    {
        return (bool) $this->db->remove(static::HIDDEN_DB_KEY, [ $tag ]);
    }

    public function rebuild()
    {
        $this->cache->destroy(static::TIME_CACHE_KEY);
        $this->cache->destroy(static::CACHE_KEY);

        $this->get(); // Rebuild cache
    }

    public function fetch($limit = 20)
    {
        $timestamps = Core\Analytics\Timestamps::span(1, 'day');

        $opts = [
            'index' => 'minds',
            'type' => 'activity',
            'search_type' => 'count',
            'body' => [
                'query' => [
                    'range' => [
                        'time_created' => [
                            'gte' => $timestamps[0]
                            ]
                        ]
                ],
                'aggs' => [
                    'minds' => [
                        'terms' => [
                            'field' => "hashtags",
                            'size' => $limit
                        ]
                    ]
                ]
            ]
        ];

        $result = (new Documents())->customQuery($opts);
        $tags = [];

        if ($result) {
            foreach ($result['aggregations']['minds']['buckets'] as $tag) {
                $tags[] = $tag['key'];
            }
        }

        return $tags;
    }

    public function fetchHidden($limit = 5000)
    {
        return $this->db->get(static::HIDDEN_DB_KEY, [ 'limit' => $limit ]) ?: [];
    }

    // Internal use

    private static function fast_array_diff($a, $b) {
        $map = array();
        foreach($a as $val) $map[$val] = 1;
        foreach($b as $val) unset($map[$val]);
        return array_keys($map);
    }
}
