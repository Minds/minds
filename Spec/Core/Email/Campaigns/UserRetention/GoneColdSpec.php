<?php

namespace Spec\Minds\Core\Email\Campaigns\UserRetention;

use Minds\Core\Email\Campaigns\UserRetention\GoneCold;
use PhpSpec\ObjectBehavior;
use Minds\Core\Email\Mailer;
use Minds\Core\Email\Manager;
use Minds\Core\Email\EmailSubscription;
use Minds\Entities\User;
use Prophecy\Argument;

class GoneColdSpec extends ObjectBehavior
{
    protected $mailer;
    protected $manager;

    public function let(Mailer $mailer, Manager $manager)
    {
        $this->beConstructedWith(null, $mailer, $manager);
        $this->mailer = $mailer;
        $this->manager = $manager;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GoneCold::class);
    }

    public function it_should_send_a_gone_cold_email(User $user)
    {
        $testGUID = 123456789;
        $testName = 'test_name';
        $testEmail = 'test@minds.com';
        $testUsername = 'testUsername';

        $user->getGUID()->shouldBeCalled()->willReturn($testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($testName);
        $user->get('guid')->shouldBeCalled()->willReturn($testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($testUsername);
        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('cold');
        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['guid']->shouldEqual($testGUID);
        $data['email']->shouldEqual($testEmail);
        $data['username']->shouldEqual($testUsername);

        $this->mailer->queue(Argument::any())->shouldBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($testGUID)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue(true);

        $this->manager->isSubscribed($testEmailSubscription)->shouldBeCalled()->willReturn(true);
        $this->send();
    }

    public function it_should_not_send_unsubscribed(User $user)
    {
        $testGUID = 123456789;
        $testName = 'test_name';
        $testEmail = 'test@minds.com';
        $testUsername = 'testUsername';

        $user->getGUID()->shouldBeCalled()->willReturn($testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($testName);
        $user->get('guid')->shouldBeCalled()->willReturn($testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($testUsername);

        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('cold');
        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($testEmail);
        $data['username']->shouldEqual($testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($testGUID)
            ->setCampaign('global')
            ->setTopic('minds_tips')
            ->setValue(true);

        $this->manager->isSubscribed($testEmailSubscription)->shouldBeCalled()->willReturn(false);
        $this->send();
    }

    public function it_should_not_blowup_without_a_manager(User $user)
    {
        $testGUID = 123456789;
        $testName = 'test_name';
        $testEmail = 'test@minds.com';
        $testUsername = 'testUsername';

        $user->getGUID()->shouldBeCalled()->willReturn($testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn('yes');
        $user->get('name')->shouldBeCalled()->willReturn($testName);
        $user->get('guid')->shouldBeCalled()->willReturn($testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($testUsername);

        $this->getCampaign()->shouldEqual('global');
        $this->getTopic()->shouldEqual('minds_tips');
        $this->getState()->shouldEqual('cold');
        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($testEmail);
        $data['username']->shouldEqual($testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();

        $testEmailSubscription = (new EmailSubscription())
            ->setUserGuid($testGUID)
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
        $testGUID = 123456789;
        $testName = 'test_name';
        $testEmail = 'test@minds.com';
        $testUsername = 'testUsername';

        $user->getGUID()->shouldBeCalled()->willReturn($testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn(false);
        $user->get('name')->shouldBeCalled()->willReturn($testName);
        $user->get('guid')->shouldBeCalled()->willReturn($testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($testUsername);

        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($testEmail);
        $data['username']->shouldEqual($testUsername);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();
        $this->send();
    }

    public function it_should_send_not_send_unsubscribed_emails(User $user)
    {
        $testGUID = 123456789;
        $testName = 'test_name';
        $testEmail = 'test@minds.com';
        $testUsername = 'testUsername';

        $user->getGUID()->shouldBeCalled()->willReturn($testGUID);
        $user->get('enabled')->shouldBeCalled()->willReturn(true);
        $user->get('name')->shouldBeCalled()->willReturn($testName);
        $user->get('guid')->shouldBeCalled()->willReturn($testGUID);
        $user->getEmail()->shouldBeCalled()->willReturn($testEmail);
        $user->get('username')->shouldBeCalled()->willReturn($testUsername);

        $this->setUser($user);
        $this->build();

        $data = $this->getTemplate()->getData();
        $data['email']->shouldEqual($testEmail);
        $data['username']->shouldEqual($testUsername);

        $this->manager->isSubscribed(Argument::type(EmailSubscription::class))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->mailer->queue(Argument::any())->shouldNotBeCalled();
        $this->send();
    }
}
