<?php

namespace Minds\Core\Email\Delegates;

use Minds\Entities\User;
use Minds\Core\Di\Di;
use Minds\Core\Suggestions\Manager as SuggestionsManager;
use Minds\Core\Onboarding\Manager as OnboardingManager;
use Minds\Interfaces\SenderInterface;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeComplete;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;

class WelcomeSender implements SenderInterface
{
    /** @var SuggestionsManager */
    private $suggestionsManager;
    /** @var OnboardingManager */
    private $onboardingManager;
    /** @var WelcomeComplete */
    private $welcomeComplete;
    /** @var WelcomeIncomplete */
    private $welcomeIncomplete;

    public function __construct(
        SuggestionsManager $suggestionsManager = null,
        OnboardingManager $onboardingManager = null,
        WelcomeComplete $welcomeComplete = null,
        WelcomeIncomplete $welcomeIncomplete = null
    ) {
        $this->suggestionsManager = $suggestionsManager ?: Di::_()->get('Suggestions\Manager');
        $this->onboardingManager = $onboardingManager ?: Di::_()->get('Onboarding\Manager');
        $this->welcomeComplete = $welcomeComplete ?: new WelcomeComplete();
        $this->welcomeIncomplete = $welcomeIncomplete ?: new WelcomeIncomplete();
    }

    public function send(User $user)
    {
        $this->onboardingManager->setUser($user);
        $campaign = $this->welcomeComplete;
        if ($this->onboardingManager->isComplete()) {
            $this->suggestionsManager->setUser($user);
            $suggestions = $this->suggestionsManager->getList();
            $campaign->setSuggestions($suggestions);
        } else {
            $campaign = $this->welcomeIncomplete;
        }
        $campaign->setUser($user);
        $campaign->send();
    }
}
