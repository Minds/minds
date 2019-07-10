<?php

namespace Spec\Minds\Core\Email\Delegates;

use Minds\Core\Email\Delegates\GoneColdSender;
use PhpSpec\ObjectBehavior;
use Minds\Core\Suggestions\Manager as SuggestionsManager;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Manager;
use Minds\Core\Email\Mailer;
use Minds\Entities\User;
use Minds\Core\Email\Campaigns\UserRetention\GoneCold;
use Minds\Core\Email\CampaignLogs\CampaignLog;

class GoneColdSenderSpec extends ObjectBehavior
{
    /** @var Manager $manager */
    private $manager;
    /** @var SuggestionsManager $manager */
    private $suggestionsManager;
    /** @var Mailer $mailer */
    private $mailer;
    /** @var GoneCold $campaign */
    private $campaign;
    private $testGUID = 123;
    private $testName = 'test_name';
    private $testEmail = 'test@minds.com';
    private $testUsername = 'testUsername';
    private $testBriefDescription = 'test brief description';

    public function let(Manager $manager, SuggestionsManager $suggestionsManager, Mailer $mailer)
    {
        $this->manager = $manager;
        $this->suggestionsManager = $suggestionsManager;
        $this->mailer = $mailer;
        $campaign = new GoneCold(null, $mailer->getWrappedObject(), $manager->getWrappedObject());
        $this->campaign = $campaign;
        $this->beConstructedWith($suggestionsManager, $campaign);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GoneColdSender::class);
    }

    public function it_should_send(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->suggestionsManager->setUser($user)->shouldBeCalled();
        $this->suggestionsManager->getList()->shouldBeCalled();

        $emailSubscription = (new EmailSubscription())
            ->setUserGuid(123)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue('true');

        $time = time();

        $campaignLog = (new CampaignLog())
            ->setReceiverGuid($this->testGUID)
            ->setTimeSent($time)
            ->setEmailCampaignId($this->campaign->getEmailCampaignId());

        $this->manager->saveCampaignLog($campaignLog)->shouldBeCalled();
        $this->manager->isSubscribed($emailSubscription)->shouldBeCalled()->willReturn(true);
        $this->send($user);
    }
}
