<?php
namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Core\Util\BigNumber;

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
        /** @var Sums $sums */
        $sums = Di::_()->get('Wire\Sums');
        $sums->setReceiver($entity->getOwnerGUID())
            ->setSender($user)
            ->setFrom((new \DateTime('midnight'))->modify("-30 days")->getTimestamp());

        $amount = $sums->getSent();

        $allowed = BigNumber::_($amount)->sub($threshold['min'])->gte(0);

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
