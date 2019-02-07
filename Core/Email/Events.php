<?php
/**
 * Email events.
 */

namespace Minds\Core\Email;

use Minds\Core\Events\Dispatcher;
use Minds\Interfaces\ModuleInterface;
use Minds\Core\Analytics\UserStates\UserActivityBuckets;

class Events implements ModuleInterface
{
    /**
     * OnInit.
     */
    public function onInit()
    {
        $provider = new Provider();
        $provider->register();
    }

    public function register()
    {
        Dispatcher::register('user_state_change', 'all', function ($opts) {
            error_log('user_state_change all');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_CASUAL, function ($opts) {
            error_log('user_state_change casual');
        });

        Dispatcher::register('user_state_change', UserActivityBuckets::STATE_COLD, function ($opts) {
            error_log('user_state_change cold');
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
    }
}
