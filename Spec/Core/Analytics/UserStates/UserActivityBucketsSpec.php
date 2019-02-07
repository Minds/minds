<?php

namespace Spec\Minds\Core\Analytics\UserStates;

use PhpSpec\ObjectBehavior;
use Guid;

class UserActivityBucketsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Analytics\UserStates\UserActivityBuckets');
    }

    public function it_should_identify_a_new_user_one_minute_old()
    {
        $guid = new Guid();
        $referenceDate = strtotime('midnight');
        $userGuid = $guid->generate(strtotime('-1 minute', $referenceDate) * 1000);

        $this->setReferenceDateMs($referenceDate * 1000)
            ->setUserGuid($userGuid);
        $this->isNewUser()->shouldEqual(true);
    }

    public function it_should_identify_a_new_user_almost_one_day_old()
    {
        $guid = new Guid();
        $referenceDate = strtotime('midnight');
        $userGuid = $guid->generate(strtotime('-24 hours +1 minute', $referenceDate) * 1000);

        $this->setReferenceDateMs($referenceDate * 1000)
            ->setUserGuid($userGuid);
        $this->isNewUser()->shouldEqual(true);
    }

    public function it_should_identify_a_day_old_user()
    {
        $guid = new Guid();
        $referenceDate = strtotime('midnight');
        $userGuid = $guid->generate(strtotime('-24 hours -1 minute', $referenceDate) * 1000);

        $this->setReferenceDateMs($referenceDate * 1000)
            ->setUserGuid($userGuid);
        $this->isNewUser()->shouldEqual(false);
    }

    public function it_should_tag_a_cold_user()
    {
        $guid = new Guid();
        $activeDayBuckets = [
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 1],
        ];
        $userGuid = $guid->generate(strtotime('-7 days') * 1000);
        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);
        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('cold');
        $this->getActivityPercentage()->shouldBeLike(number_format(0));
    }

    public function it_should_tag_a_resurrected_user()
    {
        $guid = new Guid();
        $userGuid = $guid->generate(strtotime('-1 month') * 1000);
        $activeDayBuckets = [
            ['count' => 1],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 26],
        ];

        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('resurrected');
        $this->getActivityPercentage()->shouldBeLike(number_format(1 / 7, 2));
    }

    public function it_should_tag_a_casual_user()
    {
        $guid = new Guid();
        $userGuid = $guid->generate(strtotime('-1 month') * 1000);
        $activeDayBuckets = [
            ['count' => 1],
            ['count' => 13],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 1],
        ];

        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);
        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('casual');
        $this->getActivityPercentage()->shouldBeLike(number_format(2 / 7, 2));
    }

    public function it_should_tag_a_casual_user_with_two_days()
    {
        $guid = new Guid();
        $userGuid = $guid->generate(strtotime('-1 month') * 1000);
        $activeDayBuckets = [
            ['count' => 0],
            ['count' => 1],
            ['count' => 1],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
        ];

        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('casual');
        $this->getActivityPercentage()->shouldBeLike(number_format(2 / 7, 2));
    }

    public function it_should_tag_a_curious_user()
    {
        $guid = new Guid();
        $userGuid = $guid->generate(strtotime('-1 month') * 1000);
        $activeDayBuckets = [
            ['count' => 0],
            ['count' => 1],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
        ];

        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('curious');
        $this->getActivityPercentage()->shouldBeLike(number_format(1 / 7, 2));
    }

    public function it_should_tag_a_core_user()
    {
        $guid = new Guid();
        $userGuid = $guid->generate(strtotime('-1 month') * 1000);
        $activeDayBuckets = [
            ['count' => 1],
            ['count' => 13],
            ['count' => 5],
            ['count' => 9],
            ['count' => 3],
            ['count' => 1],
            ['count' => 1],
            ['count' => 1],
        ];

        $this->setReferenceDateMs(strtotime('midnight') * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('core');
        $this->getActivityPercentage()->shouldBeLike(1);
    }

    public function it_should_tag_a_new_user()
    {
        $guid = new Guid();
        $referenceDate = strtotime('midnight');
        $userGuid = $guid->generate(strtotime('-12 hours', $referenceDate) * 1000);
        $activeDayBuckets = [
            ['count' => 1],
            ['count' => 13],
            ['count' => 5],
            ['count' => 9],
            ['count' => 3],
            ['count' => 1],
            ['count' => 1],
            ['count' => 1],
        ];

        $this->setReferenceDateMs($referenceDate * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(true);
        $this->getState()->shouldEqual('new');
        $this->getActivityPercentage()->shouldBeLike(1);
    }

    public function it_should_tag_a_new_user_just_over_the_threshold_as_curious()
    {
        $guid = new Guid();
        $referenceDate = strtotime('midnight');
        $userGuid = $guid->generate(strtotime('-24 hours -1 minute', $referenceDate) * 1000);
        $activeDayBuckets = [
            ['count' => 0],
            ['count' => 0],
            ['count' => 1],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
            ['count' => 0],
        ];

        $this->setReferenceDateMs($referenceDate * 1000)
            ->setUserGuid($userGuid)
            ->setActiveDaysBuckets($activeDayBuckets);

        $this->getUserGuid()->shouldEqual($userGuid);
        $this->isNewUser()->shouldEqual(false);
        $this->getState()->shouldEqual('curious');
        $this->getActivityPercentage()->shouldBeLike(.14);
    }
}
