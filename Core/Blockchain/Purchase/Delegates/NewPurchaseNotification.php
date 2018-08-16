<?php

/**
 * Minds New Purchase Notification Delegate
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Util\BigNumber;

class NewPurchaseNotification
{
    /** @var Config */
    protected $config;

    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($config = null, $dispatcher = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function notify(Purchase $purchase)
    {
        $amount = (int) BigNumber::_($purchase->getRequestedAmount())->div(10 ** 18)->toString();

        $message = "Your purchase of $amount Tokens is being processed.";

        $this->dispatcher->trigger('notification', 'all', [
            'to' => [ $purchase->getUserGuid() ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 'message' => $message ],
            'message' => $message,
        ]);
    }
}
