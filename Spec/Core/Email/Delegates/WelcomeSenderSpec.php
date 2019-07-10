<?php

namespace Spec\Minds\Core\Email\Delegates;

use Minds\Core\Email\Delegates\WelcomeSender;
use PhpSpec\ObjectBehavior;
use Minds\Core\Suggestions\Manager as SuggestionsManager;
use Minds\Core\Onboarding\Manager as OnboardingManager;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Manager;
use Minds\Core\Email\Mailer;
use Minds\Entities\User;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeComplete;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;
use Minds\Core\Email\CampaignLogs\CampaignLog;

class WelcomeSenderSpec extends ObjectBehavior
{
    /** @var Manager $manager */
    private $manager;
    /** @var SuggestionsManager */
    private $suggestionsManager;
    /** @var OnboardingManager */
    private $onboardingManager;
    /** @var WelcomeComplete */
    private $welcomeComplete;
    /** @var WelcomeIncomplete */
    private $welcomeIncomplete;
    private $testGUID = 123;
    private $testName = 'test_name';
    private $testEmail = 'test@minds.com';
    private $testUsername = 'testUsername';
    private $testBriefDescription = 'test brief description';

    public function let(
        Manager $manager,
        SuggestionsManager $suggestionsManager,
        OnboardingManager $onboardingManager,
        Mailer $mailer
    ) {
        $welcomeComplete = new WelcomeComplete(null, $mailer->getWrappedObject(), $manager->getWrappedObject());
        $welcomeIncomplete = new WelcomeIncomplete(null, $mailer->getWrappedObject(), $manager->getWrappedObject());
        $this->manager = $manager;
        $this->suggestionsManager = $suggestionsManager;
        $this->onboardingManager = $onboardingManager;
        $this->welcomeComplete = $welcomeComplete;
        $this->welcomeIncomplete = $welcomeIncomplete;
        $this->beConstructedWith($suggestionsManager, $onboardingManager, $welcomeComplete, $welcomeIncomplete);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WelcomeSender::class);
    }

    public function it_should_send_a_welcome_complete(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->onboardingManager->setUser($user)->shouldBeCalled();
        $this->onboardingManager->isComplete()->shouldBeCalled()->willReturn(true);
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
        ->setEmailCampaignId($this->welcomeComplete->getEmailCampaignId());

        $this->manager->saveCampaignLog($campaignLog)->shouldBeCalled();
        $this->manager->isSubscribed($emailSubscription)->shouldBeCalled()->willReturn(true);
        $this->send($user);
    }

    public function it_should_send_a_welcome_incomplete(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->onboardingManager->setUser($user)->shouldBeCalled();
        $this->onboardingManager->isComplete()->shouldBeCalled()->willReturn(false);
        $this->suggestionsManager->setUser($user)->shouldNotBeCalled();
        $this->suggestionsManager->getList()->shouldNotBeCalled();

        $emailSubscription = (new EmailSubscription())
        ->setUserGuid(123)
        ->setCampaign('global')
        ->setTopic('minds_tips')
        ->setValue('true');

        $time = time();

        $campaignLog = (new CampaignLog())
        ->setReceiverGuid($this->testGUID)
        ->setTimeSent($time)
        ->setEmailCampaignId($this->welcomeIncomplete->getEmailCampaignId());

        $this->manager->saveCampaignLog($campaignLog)->shouldBeCalled();
        $this->manager->isSubscribed($emailSubscription)->shouldBeCalled()->willReturn(true);
        $this->send($user);
    }
}
