<?php
namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use Minds\Helpers\MagicAttributes;

class Thresholds
{
    /**
     * Check if the entity can be shown to the passed user
     * @param User $user
     * @param $entity
     * @return bool
     * @throws \Exception
     */
    public function isAllowed($user, $entity)
    {
        if (!is_object($entity) || !(MagicAttributes::getterExists($entity, 'getWireThreshold') || method_exists($entity, 'getWireThreshold'))) {
            throw new \Exception('Entity cannot be paywalled');
        }

	    if ($user && ($user->guid == $entity->getOwnerEntity()->guid || $user->isAdmin())) {
            return true;
        }

        $isPaywall = false;

        if ((MagicAttributes::getterExists($entity, 'isPaywall') || method_exists($entity, 'isPaywall')) && $entity->isPaywall()) {
            $isPaywall = true;
        } elseif (method_exists($entity, 'getFlag') && $entity->getFlag('paywall')) {
            $isPaywall = true;
        }

        $threshold = $entity->getWireThreshold();

        if (!$threshold && $isPaywall) {
            $threshold = [
                'type' => 'money',
                'min' => $entity->getOwnerEntity()->getMerchant()['exclusive']['amount']
            ];
        }

        //make sure legacy posts can work
        if ($isPaywall) {

            $amount = 0;

            if (MagicAttributes::getterExists($entity, 'getOwnerGuid')) {
                $ownerGuid = $entity->getOwnerGuid();
            } else {
                $ownerGuid = $entity->getOwnerGUID();
            }

            /** @var Sums $sums */
            $sums = Di::_()->get('Wire\Sums');
            $sums->setReceiver($ownerGuid)
                ->setSender($user->guid)
                ->setFrom((new \DateTime('midnight'))->modify("-30 days")->getTimestamp());

            $amount = $sums->getSent();

            $minThreshold = $threshold['min'];

            if ($threshold['type'] === 'tokens') {
                $minThreshold = BigNumber::toPlain($threshold['min'], 18);
            }

            $allowed = BigNumber::_($amount)->sub($minThreshold)->gte(0);

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
        return true;
    }
}
