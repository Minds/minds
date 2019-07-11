<?php

namespace Minds\Core\Feeds\Top;

use Minds\Common\Repository\Response;
use Minds\Common\Urn;
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

    /** @var Search */
    private $search;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Entities */
    protected $entities;

    private $from;

    private $to;

    private $type = 'activity';

    private $subtype = '';

    public function __construct(
        $repository = null,
        $entitiesBuilder = null,
        $entities = null,
        $search = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesBuilder = $entitiesBuilder ?: new EntitiesBuilder;
        $this->entities = $entities ?: new Entities;
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
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        $opts = array_merge([
            'algorithm' => null,
            'cache_key' => null,
            'user_guid' => null,
            'container_guid' => null,
            'owner_guid' => null,
            'subscriptions' => null,
            'access_id' => null,
            'custom_type' => null,
            'offset' => 0,
            'limit' => 12,
            'type' => null,
            'sync' => false,
            'from_timestamp' => null,
            'query' => null,
            'nsfw' => null,
            'single_owner_threshold' => 36,
            'filter_hashtags' => false,
            'pinned_guids' => null,
            'as_activities' => false,
        ], $opts);

        if (isset($opts['query']) && $opts['query']) {
            $opts['query'] = str_replace('#', '', strtolower($opts['query']));
        }

        if (isset($opts['query']) && $opts['query'] && in_array($opts['type'], ['user', 'group'])) {
            $result = $this->search($opts);

            $response = new Response($result);
            return $response;
        } 

        $feedSyncEntities = [];
        $scores = [];
        $owners = [];
        $i = 0;

        foreach ($this->repository->getList($opts) as $scoredGuid) {
            if (!$scoredGuid->getGuid()) {
                continue;
            }

            $ownerGuid = $scoredGuid->getOwnerGuid() ?: $scoredGuid->getGuid();

            if ($i < $opts['single_owner_threshold']
                && isset($owners[$ownerGuid])
                && !$opts['filter_hashtags']
                && !in_array($opts['type'], [ 'user', 'group' ])
            ) {
                continue;
            }
            $owners[$ownerGuid] = true;

            ++$i; // Update here as we don't want to count skipped

            $entityType = $scoredGuid->getType() ?? 'entity';
            if (strpos($entityType, 'object:', 0) === 0) {
                $entityType = str_replace('object:', '', $entityType);
            }

            if ($opts['as_activities'] && !in_array($opts['type'], [ 'user', 'group' ])) {
                $entityType = 'activity';
            }

            $urn = implode(':', [
                'urn',
                $entityType, 
                $scoredGuid->getGuid(),
            ]);

            $feedSyncEntities[] = (new FeedSyncEntity())
                ->setGuid((string) $scoredGuid->getGuid())
                ->setOwnerGuid((string) $ownerGuid)
                ->setUrn(new Urn($urn))
                ->setTimestamp($scoredGuid->getTimestamp());

            $scores[(string) $scoredGuid->getGuid()] = $scoredGuid->getScore();
        }

        $entities = [];
        $next = '';

        if (count($feedSyncEntities) > 0) {
           $next = (string) (array_reduce($feedSyncEntities, function($carry, FeedSyncEntity $feedSyncEntity) {
               return min($feedSyncEntity->getTimestamp() ?: INF, $carry);
           }, INF) - 1);

           $hydrateGuids = array_map(function (FeedSyncEntity $feedSyncEntity) {
               return $feedSyncEntity->getGuid();
           }, array_slice($feedSyncEntities, 0, 12)); // hydrate the first 12

           $hydratedEntities = $this->entitiesBuilder->get(['guids' => $hydrateGuids]);

           foreach ($hydratedEntities as $entity) {
               if ($opts['pinned_guids'] && in_array($entity->getGuid(), $opts['pinned_guids'])) {
                   $entity->pinned = true;
               }
               if ($opts['as_activities']) {
                   $entity = $this->entities->cast($entity);
               }
               $entities[] = (new FeedSyncEntity)
                                ->setGuid($entity->getGuid())
                                ->setOwnerGuid($entity->getOwnerGuid())
                                ->setUrn($entity->getUrn())
                                ->setEntity($entity);
           }

           // TODO: Optimize this
           foreach (array_slice($feedSyncEntities, 12) as $entity) {
               $entities[] = $entity;
           }

           // TODO: confirm if the following is actually necessary
           // especially after the first 12

           /*usort($entities, function ($a, $b) use ($scores) {
               $aGuid = $a instanceof FeedSyncEntity ? $a->getGuid() : $a->guid;
               $bGuid = $b instanceof FeedSyncEntity ? $b->getGuid() : $b->guid;

               $aScore = $scores[(string) $aGuid];
               $bScore = $scores[(string) $bGuid];

               if ($aScore === $bScore) {
                   return 0;
               }

               return $aScore < $bScore ? 1 : -1;
           });*/
        }

        $response = new Response($entities);
        $response->setPagingToken($next ?: '');

        return $response;
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
                    ->setGuid((string) $row['guid'])
                    ->setOwnerGuid((string) $row['guid'])
                    ->setUrn("urn:user:{$row['guid']}")
                    ->setTimestamp($row['time_created'] * 1000);
            }
        }

        if ($opts['type'] === 'group') {
            $options = [
                'text' => $opts['query'],
                'taxonomies' => 'group',
                'sort' => 'relevant',
            ];

            $response = $this->search->query($options, $opts['limit'], $opts['offset']);
            foreach ($response as $row) {
                $feedSyncEntities[] = (new FeedSyncEntity())
                    ->setGuid($row)
                    ->setOwnerGuid(-1)
                    ->setUrn("urn:group:{$row['guid']}")
                    ->setTimestamp(0);
            }
        }

        $entities =  [];

        $hydrateGuids = array_map(function (FeedSyncEntity $feedSyncEntity) {
            return $feedSyncEntity->getGuid();
        }, array_slice($feedSyncEntities, 0, 12)); // hydrate the first 12

        if ($hydrateGuids) {
            $hydratedEntities = $this->entitiesBuilder->get(['guids' => $hydrateGuids]);

            foreach ($hydratedEntities as $entity) {
                $entities[] = (new FeedSyncEntity)
                                 ->setGuid($entity->getGuid())
                                 ->setOwnerGuid($entity->getOwnerGuid())
                                 ->setUrn($entity->getUrn())
                                 ->setEntity($entity);
            }
        }

        // TODO: Optimize this
        foreach (array_slice($feedSyncEntities, 12) as $entity) {
            $entities[] = $entity;
        }

        return $entities;
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
