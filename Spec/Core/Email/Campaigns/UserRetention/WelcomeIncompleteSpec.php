<?php

namespace Spec\Minds\Core\Email\Campaigns\UserRetention;

use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;
use PhpSpec\ObjectBehavior;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Manager;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\CampaignLogs\CampaignLog;
use Minds\Entities\User;
use Prophecy\Argument;

class WelcomeIncompleteSpec extends ObjectBehavior
{
    protected $mailer;
    protected $manager;

    private $testGUID = 123456789;
    private $testName = 'test_name';
    private $testEmail = 'test@minds.com';
    private $testUsername = 'testUsername';

    public function let(Mailer $mailer, Manager $manager)
    {
        $this->beConstructedWith(null, $mailer, $manager);
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WelcomeIncomplete::class);
    }

    public function it_should_send_a_welcome_incomplete_email(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('new');
        $this->setUser($user);
        $message = $this->build();
        $message->getSubject()->shouldEqual('Welcome to Minds');
        $to = $message->getTo()[0]['name']->shouldEqual($this->testName);
        $to = $message->getTo()[0]['email']->shouldEqual($this->testEmail);

        $data = $this->getTemplate()->getData();
        $data['guid']->shouldEqual($this->testGUID);
        $data['email']->shouldEqual($this->testEmail);
        $data['username']->shouldEqual($this->testUsername);

        $this->mailer->queue(Argument::any())->shouldBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($this->testGUID)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue(true);

        $time = time();

        $campaignLog = (new CampaignLog())
            ->setReceiverGuid($this->testGUID)
            ->setTimeSent($time)
            ->setEmailCampaignId($this->getEmailCampaignId()->getWrappedObject());

        $this->manager->saveCampaignLog($campaignLog)->shouldBeCalled();
        $this->manager->isSubscribed($testEmailSubscription)->shouldBeCalled()->willReturn(true);

        $this->send($time);
    }

    public function it_should_not_send_unsubscribed(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('new');
        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($this->testEmail);
        $data['username']->shouldEqual($this->testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($this->testGUID)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue(true);

        $this->manager->isSubscribed($testEmailSubscription)->shouldBeCalled()->willReturn(false);
        $this->send();
    }

    public function it_should_not_blowup_without_a_manager(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('new');
        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($this->testEmail);
        $data['username']->shouldEqual($this->testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($this->testGUID)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue(true);

        $this->manager = null;
        $this->send();
    }

    public function it_should_not_blowup_without_a_user(User $user)
    {
        $this->mailer->queue(Argument::any())->shouldNotBeCalled();
        $this->send();
    }

    public function it_should_not_send_disabled(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn(false);
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($this->testEmail);
        $data['username']->shouldEqual($this->testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();
        $this->send();
    }

    public function it_should_send_not_send_unsubscribed_emails(User $user)
    {
        $user->getGUID()->shouldBeCalled()->willReturn($this->testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn(true);
        $user->get('name')->shouldBeCalled()->willReturn($this->testName);
        $user->get('guid')->shouldBeCalled()->willReturn($this->testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($this->testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($this->testUsername);

        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($this->testEmail);
        $data['username']->shouldEqual($this->testUsername);

        $this->manager->isSubscribed(Argument::type(EmailSubscription::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();
        $this->send();
    }
}
