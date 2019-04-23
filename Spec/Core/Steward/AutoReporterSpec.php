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

class AutoReporterSpec extends ObjectBehavior
{
    private $config;
    private $entitiesBuilder;
    private $reportManager;
    private $juryManager;
    private $moderationManager;

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
        $this->config->get('steward_guid')->willReturn(123);
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

    public function it_should_report_a_bad_word()
    {
        $entity = (new Entity())
            ->set('guid', 456)
            ->set('owner_guid', 789)
            ->set('message', 'this is only a test: nigger');
        $reason = new Reason(Reason::REASON_NSFW, Reason::REASON_NSFW_RACE, 10);

        $report = (new Reports\Report())
            ->setEntityGuid($entity->guid)
            ->setEntityOwnerGuid($entity->get('owner_guid'));
        $autoReport = (new Reports\UserReports\UserReport())
            ->setReport($report);

        $this->reportManager->add($autoReport)->shouldBeCalled();
        $this->validate($entity);
    }
}
