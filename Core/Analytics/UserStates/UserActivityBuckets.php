<?php
/**
 * User states.
 */

namespace Minds\Core\Analytics\UserStates;

use Minds\Traits\MagicAttributes;
use GUID;

class UserActivityBuckets
{
    use MagicAttributes;

    const STATE_CASUAL = 'casual';
    const STATE_COLD = 'cold';
    const STATE_CORE = 'core';
    const STATE_CURIOUS = 'curious';
    const STATE_NEW = 'new';
    const STATE_RESURRECTED = 'resurrected';
    const THRESHOLD_CASUAL_USER = .25;
    const THRESHOLD_CORE_USER = .75;
    const NEW_USER_AGE_HOURS = 24;

    /** @var string $userGuid */
    private $userGuid;

    /** @var long $referenceDateMs */
    private $referenceDateMs;

    /** @var string $daysActiveBucket */
    private $daysActiveBuckets = [];

    //Values derived from buckets
    private $numberOfDays = 0;
    private $mostRecentDayCount = 0;
    private $oldestDayCount = 0;
    private $activityPercentage = 0;

    public function isNewUser()
    {
        $guid = new Guid();
        $newUserThresholdTimestamp = strtotime('-'.static::NEW_USER_AGE_HOURS.' hours', $this->referenceDateMs / 1000);
        $maxNewUserThresholdTimestamp = strtotime('+'.static::NEW_USER_AGE_HOURS.' hours', $newUserThresholdTimestamp);

        $referenceGuid = $guid->generate($newUserThresholdTimestamp * 1000);
        $maxReferenceGuid = $guid->generate($maxNewUserThresholdTimestamp * 1000);

        $is = intval($this->userGuid) >= intval($referenceGuid)
            && intval($this->userGuid) < intval($maxReferenceGuid);

        return $is;
    }

    public function setActiveDaysBuckets($buckets)
    {
        $this->daysActiveBuckets = $buckets;
        $this->numberOfDays = count($this->daysActiveBuckets);
        $this->mostRecentDayCount = $this->daysActiveBuckets[0]['count'];
        $this->oldestDayCount = $this->daysActiveBuckets[$this->numberOfDays - 1]['count'];

        return $this;
    }

    public function getActiveDayCount()
    {
        $activeDayCount = 0;
        //increment activity for each day save for the oldest day used to to determine if a user went cold
        for ($dayIndex = 0; $dayIndex <= $this->numberOfDays - 2; ++$dayIndex) {
            if ($this->daysActiveBuckets[$dayIndex]['count'] > 0) {
                ++$activeDayCount;
            }
        }

        return $activeDayCount;
    }

    public function getActivityPercentage()
    {
        return number_format($this->getActiveDayCount() / ($this->numberOfDays - 1), 2);
    }

    public function getState()
    {
        if ($this->isNewUser()) {
            return static::STATE_NEW;
        } elseif ($this->getActivityPercentage() >= static::THRESHOLD_CORE_USER) {
            return static::STATE_CORE;
        } elseif ($this->getActivityPercentage() >= static::THRESHOLD_CASUAL_USER) {
            return static::STATE_CASUAL;
        } elseif ($this->mostRecentDayCount > 0 && $this->getActiveDayCount() == 1) {
            return static::STATE_RESURRECTED;
        } elseif ($this->oldestDayCount > 0 && $this->getActiveDayCount() == 0) {
            return static::STATE_COLD;
        } elseif ($this->getActiveDayCount() >= 1) {
            return static::STATE_CURIOUS;
        }
    }
}
