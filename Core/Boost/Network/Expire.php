<?php

namespace Minds\Core\Boost\Network;

use Minds\Core;
use Minds\Core\Data;

class Expire
{
    protected $boost;
    protected $mongo;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');

    }

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

//        $handler = Core\Boost\Factory::build($this->getBoostHandler());
//        if (method_exists($handler, 'expireBoost')) {
//            $handler->expireBoost($this->boost);
//            return true;
//        }
//        return false;


        if ($this->boost->getState() == 'completed') {
            return true; //already completed
        }

        $this->boost->setState('completed')
            ->save();

        $this->mongo->remove("boost", ['_id' => $this->boost->getId()]);

        Core\Events\Dispatcher::trigger('boost:completed', 'boost', ['boost' => $this->boost]);

        Core\Events\Dispatcher::trigger('notification', 'boost', [
            'to' => [$this->boost->getOwner()->guid],
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
