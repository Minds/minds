<?php
/**
 * Grabs the interactions for the time period and syncs with the search results
 */
namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Helpers;

class InteractionsSync
{
    const LIMIT = 10;

    protected $indexer;

    public function __construct($indexer = null, $cql = null, $cache = null)
    {
        $this->indexer = $indexer ?: Di::_()->get('Search\Index');
    }

    /**
     * Sync the interactions for a given entity
     * @param $entity
     * @return void
     */
    public function sync($entity)
    {
        $interactions = 0;
        switch ($entity->getType()) {
            case "activity":
            case "object":
                $interactions += Helpers\Counters::get($entity->getGuid(), 'thumbs:up');
                break;
            case "user":
                $interactions += $entity->getSubscribersCount();
                break;
        }

        if (Helpers\MagicAttributes::setterExists($entity, 'setInteractions')) {
            $entity->setInteractions($interactions);
        } else {
            $entity->interactions = $interactions;
        }

        $this->indexer->index($entity);
    }

}
