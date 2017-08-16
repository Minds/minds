<?php
namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;

class Thresholds
{
    /**
     * Check if the entity can be shown to the passed user
     */
    public function isAllowed($user, $entity)
    {
        if (!is_object($entity) || !method_exists($entity, 'getWireThreshold')) {
            throw new \Exception('Entity cannot be paywalled');
        }

        if (is_object($user)) {
            $user = $user->guid;
        }

        $threshold = $entity->getWireThreshold();

        //make sure legacy posts can work
        if (!$threshold && $entity->isPaywall()) {
            $threshold = [
              'type' => 'money',
              'min' => $entity->getOwnerEntity()->getMerchant()['exclusive']['amount']
            ];
        }

        $amount = 0;
        $repository = Di::_()->get('Wire\Repository');
        if ($threshold['type'] == 'points') {
            $amount = $repository->getSumBySenderForReceiver($user, $entity->getOwnerGUID(), 'points', (new \DateTime('midnight'))->modify("-30 days"));
        } else {
            $amount = $repository->getSumBySenderForReceiver($user, $entity->getOwnerGUID(), 'money', (new \DateTime('midnight'))->modify("-30 days"));
        }

        return $amount - $threshold['min'] >= 0;
    }
}
