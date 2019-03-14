<?php

namespace Minds\Core\Feeds\Top;

use Minds\Core\Feeds\FeedSyncEntity;
use Minds\Core\Di\Di;
use Minds\Core\Search\Search;
use Minds\Entities\Entity;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Trending\Aggregates;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var CachedRepository */
    protected $cachedRepository;

    /** @var Search */
    private $search;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    private $from;

    private $to;

    private $type = 'activity';

    private $subtype = '';

    public function __construct(
        $repository = null,
        $entitiesBuilder = null,
        $cachedRepository = null,
        $search = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder;
        $this->cachedRepository = $cachedRepository ?: new CachedRepository;
        $this->search = $search ?: Di::_()->get('Search\Search');

        $this->from = strtotime('-7 days') * 1000;
        $this->to = time() * 1000;
    }

    /**
     * @param string $type
     * @return Manager
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $subtype
     * @return Manager
     */
    public function setSubtype($subtype)
    {
        $this->subtype = $subtype;
        return $this;
    }

    /**
     * @param array $opts
     * @return Entity[]
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'algorithm' => null,
            'cache_key' => null,
            'user_guid' => null,
            'container_guid' => null,
            'custom_type' => null,
            'offset' => 0,
            'limit' => 12,
            'rating' => 2,
            'type' => null,
            'sync' => false,
            'query' => null,
            'nsfw' => [ ],
        ], $opts);

        if (isset($opts['query']) && in_array($opts['type'], ['user', 'group'])) {
            return $this->search($opts);
        }

        $feedSyncEntities = [];
        $scores = [];

        $this->cachedRepository
            ->setKey($opts['cache_key']);

        foreach ($this->repository->getList($opts) as $scoredGuid) {
            if (!$scoredGuid->getGuid()) {
                continue;
            }

            $feedSyncEntities[] = (new FeedSyncEntity())
                ->setGuid($scoredGuid->getGuid())
                ->setOwnerGuid($scoredGuid->getOwnerGuid());

            $scores[(string) $scoredGuid->getGuid()] = $scoredGuid->getScore();
        }

        $entities = [];
        if (count($feedSyncEntities) > 0) {
            if (!$opts['sync']) {
                $guids = array_map(function (FeedSyncEntity $feedSyncEntity) {
                    return $feedSyncEntity->getGuid();
                }, $feedSyncEntities);

                $entities = $this->entitiesBuilder->get(['guids' => $guids]);
            } else {
                $entities = $feedSyncEntities;
            }

            usort($entities, function ($a, $b) use ($scores) {
                $aGuid = $a instanceof FeedSyncEntity ? $a->getGuid() : $a->guid;
                $bGuid = $b instanceof FeedSyncEntity ? $b->getGuid() : $b->guid;

                $aScore = $scores[(string) $aGuid];
                $bScore = $scores[(string) $bGuid];

                if ($aScore === $bScore) {
                    return 0;
                }

                return $aScore < $bScore ? 1 : -1;
            });
        }

        return $entities;
    }

    /**
     * @param array $opts
     * @return array
     * @throws \Exception
     */
    private function search(array $opts = [])
    {
        $feedSyncEntities = [];

        if (!in_array($opts['type'], [ 'user', 'group' ])) {
            return [];
        }

        if ($opts['type'] === 'user') {
            $response = $this->search->suggest('user', $opts['query'], $opts['limit']);
            foreach ($response as $row) {
                $feedSyncEntities[] = (new FeedSyncEntity())
                    ->setGuid($row['guid'])
                    ->setOwnerGuid($row['guid']);
            }
        }

        if ($opts['type'] === 'group') {
            $options = [
                'text' => $opts['query'],
                'taxonomies' => 'group',
                //'mature' => count($opts['nsfw']) > 0,
                'sort' => 'relevant',
                'rating' => count($opts['nsfw']) > 0 ? 3 : 2,
            ];

            $response = $this->search->query($options, $opts['limit'], $opts['offset']);
            foreach ($response as $row) {
                $feedSyncEntities[] = (new FeedSyncEntity())
                    ->setGuid($row)
                    ->setOwnerGuid(-1);
            }
        }

        return $feedSyncEntities;
    }

    public function run($opts = [])
    {
        $opts = array_merge([
            'period' => null,
            'metric' => null,
        ], $opts);

        $maps = [
            '12h' => [
                'period' => '12h',
                'from' => strtotime('-12 hours') * 1000,
            ],
            '24h' => [
                'period' => '24h',
                'from' => strtotime('-24 hours') * 1000,
            ],
            '7d' => [
                'period' => '7d',
                'from' => strtotime('-7 days') * 1000,
            ],
            '30d' => [
                'period' => '30d',
                'from' => strtotime('-30 days') * 1000,
            ],
            '1y' => [
                'period' => '1y',
                'from' => strtotime('-1 year') * 1000,
            ],
        ];

        $period = $opts['period'];

        if (!isset($maps[$period]['from'])) {
            throw new \Exception('Invalid period');
        }

        $this->from = $maps[$period]['from'];

        $type = $this->type;
        if ($this->subtype) {
            $type = implode(':', [$this->type, $this->subtype]);
        }

        switch ($opts['metric']) {
            case 'up':
                $metricMethod = 'getVotesUp';
                $metricId = 'votes:up';
                $sign = 1;
                break;

            case 'down':
                $metricMethod = 'getVotesDown';
                $metricId = 'votes:down';
                $sign = -1;
                break;

            default:
                throw new \Exception('Invalid metric');
        }

        //sync
        $i = 0;
        foreach ($this->{$metricMethod}() as $guid => $count) {
            $countValue = $sign * $count;

            $metric = new MetricsSync();
            $metric
                ->setGuid($guid)
                ->setType($type)
                ->setMetric($metricId)
                ->setCount($countValue)
                ->setPeriod($maps[$period]['period'])
                ->setSynced(time());
            try {
                $this->repository->add($metric);
            } catch (\Exception $e) {
            }

            $i++;
            echo "\n$i: $guid -> $metricId = $countValue";
        }
        // clear any pending bulk inserts
        $this->repository->bulk();
    }


    protected function getVotesUp()
    {
        $aggregates = new Aggregates\Votes;
        $aggregates->setLimit(10000);
        $aggregates->setType($this->type);
        $aggregates->setSubtype($this->subtype);
        $aggregates->setFrom($this->from);
        $aggregates->setTo($this->to);

        return $aggregates->get();
    }

    protected function getVotesDown()
    {
        $aggregates = new Aggregates\DownVotes;
        $aggregates->setLimit(10000);
        $aggregates->setType($this->type);
        $aggregates->setSubtype($this->subtype);
        $aggregates->setFrom($this->from);
        $aggregates->setTo($this->to);

        return $aggregates->get();
    }

}
