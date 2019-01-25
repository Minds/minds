<?php

namespace Minds\Core\Feeds\Top;

use Minds\Entities\Entity;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Trending\Aggregates;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var CachedRepository */
    protected $cachedRepository;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    private $from;

    private $to;

    private $type = 'activity';

    private $subtype = '';

    public function __construct(
        $repository = null,
        $entitiesBuilder = null,
        $cachedRepository = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder;
        $this->cachedRepository = $cachedRepository ?: new CachedRepository;

        $this->from = strtotime('-7 days') * 1000;
        $this->to = time() * 1000;
    }

    /**
     * @param array $opts
     * @return Entity[]
     * @throws \Exception
     */
    public
    function getList(array $opts = [])
    {
        $opts = array_merge([
            'cache_key' => null,
            'user_guid' => null,
            'container_guid' => null,
            'offset' => 0,
            'limit' => 12,
            'rating' => 1,
            'type' => null,
        ], $opts);

        $guids = [];
        $scores = [];

        $this->cachedRepository
            ->setKey($opts['cache_key']);

        foreach ($this->cachedRepository->getList($opts) as list($guid, $score)) {
            if (!$guid) {
                continue;
            }

            $guids[] = $guid;
            $scores[(string) $guid] = $score;
        }

        $entities = [];
        if (count($guids) > 0) {
            $entities = $this->entitiesBuilder->get(['guids' => $guids]);

            usort($entities, function ($a, $b) use ($scores) {
                $aScore = $scores[(string) $a->guid];
                $bScore = $scores[(string) $b->guid];

                if ($aScore === $bScore) {
                    return 0;
                }

                return $bScore < $aScore ? 1 : -1;
            });
        }

        return $entities;
    }

    public function run($opts = [])
    {
        $opts = array_merge([
            'type' => 'activity',
            'subtype' => '',
            'period' => null,
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

        $type = implode(':', [$this->type, $this->subtype]);
        if (!$this->subtype) {
            $type = $this->type;
        }

        //sync
        foreach ($this->getVotesUp() as $guid => $count) {
            $metric = new MetricsSync();
            $metric
                ->setGuid($guid)
                ->setType($type)
                ->setMetric('votes:up')
                ->setCount($count)
                ->setPeriod($maps[$period]['period'])
                ->setSynced(time());
            try {
                $this->repository->add($metric);
            } catch (\Exception $e) {
            }

            echo "\nUP:$guid: $count";
        }

        //sync
        foreach ($this->getVotesDown() as $guid => $count) {
            $metric = new MetricsSync();
            $metric
                ->setGuid($guid)
                ->setType($type)
                ->setMetric('votes:down')
                ->setCount($count * -1)
                ->setPeriod($maps[$period]['period'])
                ->setSynced(time());
            try {
                $this->repository->add($metric);
            } catch (\Exception $e) {
            }

            echo "\nDOWN:$guid: " . ($count * -1);
        }
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
