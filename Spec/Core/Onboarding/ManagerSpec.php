<?php

namespace Spec\Minds\Core\Onboarding;

use Minds\Core\Config;
use Minds\Core\Onboarding\Delegates\OnboardingDelegate;
use Minds\Core\Onboarding\Manager;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;

class ManagerSpec extends ObjectBehavior
{
    /** @var OnboardingDelegate[] */
    protected $delegates;

    /** @var Config */
    protected $config;

    public function let(
        OnboardingDelegate $onboardingDelegate1,
        OnboardingDelegate $onboardingDelegate2,
        OnboardingDelegate $onboardingDelegate3,
        Config $config
    ) {
        $this->delegates = [
            'delegate1' => $onboardingDelegate1,
            'delegate2' => $onboardingDelegate2,
            'delegate3' => $onboardingDelegate3,
        ];

        $this->config = $config;

        $this->beConstructedWith($this->delegates, $this->config);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    public function it_should_check_if_was_onboarding_shown(User $user)
    {
        $this->config->get('onboarding_modal_timestamp')
            ->shouldBeCalled()
            ->willReturn(900);

        $user->wasOnboardingShown()
            ->shouldBeCalled()
            ->willReturn(true);

        $user->getTimeCreated()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this
            ->setUser($user)
            ->wasOnboardingShown()
            ->shouldReturn(true);
    }

    public function it_should_check_if_user_is_older_than_feature(User $user)
    {
        $this->config->get('onboarding_modal_timestamp')
            ->shouldBeCalled()
            ->willReturn(900);

        $user->wasOnboardingShown()
            ->shouldNotBeCalled();

        $user->getTimeCreated()
            ->shouldBeCalled()
            ->willReturn(800);

        $this
            ->setUser($user)
            ->wasOnboardingShown()
            ->shouldReturn(true);
    }

    public function it_should_set_onboarding_shown(User $user)
    {
        $user->setOnboardingShown(true)
            ->shouldBeCalled()
            ->willReturn($user);

        $user->save()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this
            ->setUser($user)
            ->setOnboardingShown(true)
            ->shouldReturn(true);
    }

    public function it_should_get_creator_frequency(User $user)
    {
        $user->getCreatorFrequency()
            ->shouldBeCalled()
            ->willReturn('rarely');

        $this
            ->setUser($user)
            ->getCreatorFrequency()
            ->shouldReturn('rarely');
    }

    public function it_should_set_creator_frequency(User $user)
    {
        $user->setCreatorFrequency('rarely')
            ->shouldBeCalled()
            ->willReturn($user);

        $user->save()
            ->shouldBeCalled()
            ->willReturn(1000);

        $this
            ->setUser($user)
            ->setCreatorFrequency('rarely')
            ->shouldReturn(true);
    }

    public function it_should_get_all_items()
    {
        $this
            ->getAllItems()
            ->shouldReturn([
                'delegate1',
                'delegate2',
                'delegate3',
            ]);
    }

    public function it_should_get_completed_items(User $user)
    {
        $this->delegates['delegate1']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delegates['delegate2']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->delegates['delegate3']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setUser($user)
            ->getCompletedItems()
            ->shouldReturn([
                'delegate1',
                'delegate3',
            ]);
    }

    public function it_should_mark_a_user_complete(User $user)
    {
        $this
            ->setUser($user)
            ->getAllItems()
            ->shouldReturn([
                'delegate1',
                'delegate2',
                'delegate3',
            ]);

        $this->delegates['delegate1']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delegates['delegate2']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delegates['delegate3']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->isComplete()->shouldReturn(true);
    }

    public function it_should_mark_a_user_incomplete(User $user)
    {
        $this
            ->setUser($user)
            ->getAllItems()
            ->shouldReturn([
                'delegate1',
                'delegate2',
                'delegate3',
            ]);

        $this->delegates['delegate1']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delegates['delegate2']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delegates['delegate3']
            ->isCompleted($user)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->isComplete()->shouldReturn(false);
    }
}
