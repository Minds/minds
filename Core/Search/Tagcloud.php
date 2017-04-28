<?php
/**
 * Hashtags / Tag Cloud
 */
namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;

class Tagcloud
{
    const LIMIT = 10;
    const CACHE_DURATION = 60 * 60;

    const CACHE_KEY = 'trending:hashtags';
    const TIME_CACHE_KEY = 'trending:hashtags:time';
    const HIDDEN_DB_KEY = 'trending:hashtags:hidden';

    protected $cql;
    protected $cache;

    public function __construct($cql = null, $cache = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
        $this->cache = $cache ?: Di::_()->get('Cache\Apcu');
    }

    public function get()
    {
        if ($cached = $this->cache->get(static::CACHE_KEY)) {
            $result = json_decode($cached, true);
        } else {
            $tags = $this->fetch(static::LIMIT * 3);
            $hiddenRows = $this->fetchHidden(10000); // @todo: implement token() pagination loop, right now it's too unreliable

            if ($hiddenRows) {
                foreach ($hiddenRows as $hiddenRow) {
                    $index = array_search($hiddenRow['column1'], $tags);
                    if ($index === false) continue;
                    unset($tags[$index]);
                }
            }

            $result = array_slice($tags, 0, static::LIMIT, false);

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
        $query = new Prepared\Custom();

        $query->query("INSERT INTO entities_by_time (key, column1, value) VALUES (?, ?, ?)", [
            static::HIDDEN_DB_KEY,
            (string) $tag,
            (string) time(),
        ]);

        try {
            $result = $this->cql->request($query);
        } catch (\Exception $e) {
            error_log('[Tagcloud::hide] ' . $e->getMessage());
            return false;
        }

        return true;
    }

    public function unhide($tag)
    {
        $query = new Prepared\Custom();

        $query->query("DELETE FROM entities_by_time WHERE key = ? AND column1 = ?", [
            static::HIDDEN_DB_KEY,
            (string) $tag,
        ]);

        try {
            $result = $this->cql->request($query);
        } catch (\Exception $e) {
            error_log('[Tagcloud::unhide] ' . $e->getMessage());
            return false;
        }

        return true;
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

    public function fetchHidden($limit = 500)
    {
        $query = 'SELECT * from entities_by_time WHERE key = ? LIMIT ?';
        $values = [ static::HIDDEN_DB_KEY, (int) $limit ];

        $prepared = new Prepared\Custom();
        $prepared->query($query, $values);

        return $this->cql->request($prepared);
    }

    // Internal use

    private static function fast_array_diff($a, $b) {
        $map = array();
        foreach($a as $val) $map[$val] = 1;
        foreach($b as $val) unset($map[$val]);
        return array_keys($map);
    }
}
