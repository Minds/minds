<?php

namespace Spec\Minds\Core\Steward;

use Minds\Core\Steward\AutoReporter;
use PhpSpec\ObjectBehavior;
use Minds\Core\Config\Config;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Entity;
use Minds\Entities\User;
use Minds\Core\Reports;
use Minds\Core\Steward\Reason;
use Minds\Core\Reports\Jury\Decision;
use Prophecy\Argument;

class AutoReporterSpec extends ObjectBehavior
{
    private $config;
    private $entitiesBuilder;
    private $reportManager;
    private $juryManager;
    private $moderationManager;
    private $stewardUser;

    public function let(Config $config,
        EntitiesBuilder $entitiesBuilder,
        Reports\UserReports\Manager $reportManager,
        Reports\Jury\Manager $juryManager,
        Reports\Manager $moderationManager)
    {
        $this->config = $config;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->reportManager = $reportManager;
        $this->juryManager = $juryManager;
        $this->moderationManager = $moderationManager;

        $stewardUser = (new User())
            ->set('guid', 123);

        $this->stewardUser = $stewardUser;
        $this->config->get('steward_guid')->willReturn(123);
        $this->config->get('steward_autoconfirm')->willReturn(null);
        $this->entitiesBuilder->single(123)->willReturn($stewardUser);
        $this->beConstructedWith($this->config,
            $this->entitiesBuilder,
            $this->reportManager,
            $this->juryManager,
            $this->moderationManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(AutoReporter::class);
    }

    public function it_should_report_a_bad_word_and_cast_a_decision()
    {
        $this->config->get('steward_autoconfirm')->willReturn(true);
        $entity = (new Entity())
            ->set('guid', 456)
            ->set('owner_guid', 789)
            ->set('message', 'this is only a test: nigger');

        $report = (new Reports\Report())
            ->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->get('owner_guid'));

        $autoReport = (new Reports\UserReports\UserReport())
            ->setReport($report)
            ->setReporterGuid($this->stewardUser->guid)
            ->setReasonCode(Reason::REASON_NSFW)
            ->setSubReasonCode(Reason::REASON_NSFW_RACE)
            ->setTimestamp(1);

        $decision = (new Decision())
                ->setAppeal(null)
                ->setAction('uphold')
                ->setReport($report)
                ->setUphold(true)
                ->setTimestamp(1)
                ->setJurorGuid($this->stewardUser->guid)
                ->setJurorHash(null);

        $this->reportManager->add($autoReport)->shouldBeCalled();
        $this->moderationManager->getReport($entity->guid)
            ->shouldBeCalled()->willReturn($report);

        $this->juryManager->cast($decision)->shouldBeCalled();
        $scoredReason = $this->validate($entity, 1)->getWrappedObject();
        expect($scoredReason->getReasonCode())->toEqual(Reason::REASON_NSFW);
        expect($scoredReason->getSubreasonCode())->toEqual(Reason::REASON_NSFW_RACE);
        expect($scoredReason->getWeight())->toEqual(10);
    }

    public function it_should_report_bad_words()
    {
        $entity = (new Entity())
            ->set('guid', 456)
            ->set('owner_guid', 789)
            ->set('message', 'this is only a test: adult, amateur, #anal, #ass, #babe');

        $report = (new Reports\Report())
            ->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->get('owner_guid'));

        $autoReport = (new Reports\UserReports\UserReport())
            ->setReport($report)
            ->setReporterGuid($this->stewardUser->guid)
            ->setReasonCode(Reason::REASON_NSFW)
            ->setSubReasonCode(Reason::REASON_NSFW_PORNOGRAPHY)
            ->setTimestamp(1);

        $this->reportManager->add($autoReport)->shouldBeCalled();
        $scoredReason = $this->validate($entity, 1)->getWrappedObject();
        expect($scoredReason->getReasonCode())->toEqual(Reason::REASON_NSFW);
        expect($scoredReason->getSubreasonCode())->toEqual(Reason::REASON_NSFW_PORNOGRAPHY);
        expect($scoredReason->getWeight())->toEqual(4);
    }

    public function it_should_not_report_words()
    {
        $entity = (new Entity())
            ->set('guid', 456)
            ->set('owner_guid', 789)
            ->set('message', 'this is only a test');

        $report = (new Reports\Report())
            ->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->get('owner_guid'));

        $autoReport = (new Reports\UserReports\UserReport())
            ->setReport($report)
            ->setReporterGuid($this->stewardUser->guid)
            ->setReasonCode(Reason::REASON_NSFW)
            ->setSubReasonCode(Reason::REASON_NSFW_PORNOGRAPHY)
            ->setTimestamp(1);

        $this->reportManager->add(Argument::any())->shouldNotBeCalled();
        $scoredReason = $this->validate($entity, 1)->getWrappedObject();
        expect($scoredReason)->toBeNull();
    }

    public function it_should_not_report_below_threshold()
    {
        $entity = (new Entity())
            ->set('guid', 456)
            ->set('owner_guid', 789)
            ->set('message', 'this is only a test: adult asian');

        $report = (new Reports\Report())
            ->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->get('owner_guid'));

        $autoReport = (new Reports\UserReports\UserReport())
            ->setReport($report)
            ->setReporterGuid($this->stewardUser->guid)
            ->setReasonCode(Reason::REASON_NSFW)
            ->setSubReasonCode(Reason::REASON_NSFW_PORNOGRAPHY)
            ->setTimestamp(1);

        $this->reportManager->add(Argument::any())->shouldNotBeCalled();
        $scoredReason = $this->validate($entity, 1)->getWrappedObject();
        expect($scoredReason->getReasonCode())->toEqual(Reason::REASON_NSFW);
        expect($scoredReason->getSubreasonCode())->toEqual(Reason::REASON_NSFW_PORNOGRAPHY);
        expect($scoredReason->getWeight())->toEqual(2);
    }
}
