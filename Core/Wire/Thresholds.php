<?php
namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;

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

        switch ($threshold['type']) {
            case 'points':
                $amount = $repository->getSumBySenderForReceiver($user, $entity->getOwnerGUID(), 'points', (new \DateTime('midnight'))->modify("-30 days"));
                break;
            case 'money':
                $amount = $repository->getSumBySenderForReceiver($user, $entity->getOwnerGUID(), 'money', (new \DateTime('midnight'))->modify("-30 days"));
                break;
            case 'tokens':
                $amount = $repository->getSumBySenderForReceiver($user, $entity->getOwnerGUID(), 'tokens', (new \DateTime('midnight'))->modify("-30 days"));
                break;
        }

        $allowed = $amount - $threshold['min'] >= 0;

        if ($allowed) {
            return true;
        }

        //Plus hack
        if ($entity->owner_guid == '730071191229833224') {
            $plus = (new Core\Plus\Subscription())->setUser($user);

            if ($plus->isActive()) {
                return true; 
            }
        }

        return false; 
    }
}
