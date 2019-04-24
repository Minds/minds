<?php
/**
 * Email events.
 */

namespace Minds\Core\Email;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Analytics\UserStates\UserActivityBuckets;
use Minds\Core\Email\Campaigns\UserRetention\GoneCold;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeComplete;
use Minds\Core\Email\Campaigns\UserRetention\WelcomeIncomplete;
use Minds\Entities\User;
use Minds\Core\Suggestions\Manager as SuggestionManager;

class Events
{
    public function register()
    {
        Dispatcher::register('user_state_change', 'all', function ($opts) {
            error_log('user_state_change all');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CASUAL, function ($opts) {
            error_log('user_state_change casual');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CORE, function ($opts) {
            error_log('user_state_change core');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CURIOUS, function ($opts) {
            error_log('user_state_change curious');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_NEW, function ($opts) {
            error_log('user_state_change new');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_RESURRECTED, function ($opts) {
            error_log('user_state_change resurrected');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_COLD, function ($opts) {
            error_log('user_state_change cold');
            $params = $opts->getParameters();
            $user = new User($params['user_guid']);
            $manager = new SuggestionManager();
            $manager->setUser($user);
            $suggestions = $manager->getList();
            $campaign = (new GoneCold())
                ->setUser($user)
                ->setSuggestions($suggestions);
            $campaign->send();
        });

        Dispatcher::register('welcome_email', 'all', function ($opts) {
            error_log('welcome_email');
            $params = $opts->getParameters();
            $user = new User($params['user_guid']);
            $onboardingManager = Di::_()->get('Onboarding\Manager');
            $onboardingManager->setUser($user);

            if ($onboardingManager->isComplete()) {
                $campaign = (new WelcomeComplete());
                $suggestionManager = Di::_()->get('Suggestions\Manager');
                $suggestionManager->setUser($user);
                $suggestions = $suggestionManager->getList();
                $campaign->setSuggestions($suggestions);
            } else {
                $campaign = (new WelcomeIncomplete());
                error_log('Sending Welcome Incomplete');
            }
            $campaign->setUser($user);
            $campaign->send();
        });
    }
}
