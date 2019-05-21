<?php

namespace Minds\Core\Boost\Network;

use Minds\Core;
use Minds\Core\Data;

class Expire
{
    /** @var Boost $boost */
    protected $boost;

    /** @var Manager $manager */
    protected $manager;

    public function __construct($manager = null)
    {
        $this->manager = $manager ?: new Manager;
    }

    /**
     * Set the boost to expire
     * @param Boost $boost
     * @return void
     */
    public function setBoost($boost)
    {
        $this->boost = $boost;
    }

    /**
     * Expire a boost from the queue
     * @return bool
     */
    public function expire()
    {
        if (!$this->boost) {
            return false;
        }

        if ($this->boost->getState() == 'completed') {
            // Re-sync ElasticSearch
            $this->manager->resync($this->boost);

            // Already completed
            return true;
        }

        $this->boost->setCompletedTimestamp(round(microtime(true) * 1000));

        $this->manager->update($this->boost);

        Core\Events\Dispatcher::trigger('boost:completed', 'boost', ['boost' => $this->boost]);

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to' => [$this->boost->getOwnerGuid()],
            'from' => 100000000000000519,
            'entity' => $this->boost->getEntity(),
            'notification_view' => 'boost_completed',
            'params' => [
                'impressions' => $this->boost->getImpressions(),
                'title' => $this->boost->getEntity()->title ?: $this->boost->getEntity()->message
            ],
            'impressions' => $this->boost->getImpressions()
        ]);
    }
}
