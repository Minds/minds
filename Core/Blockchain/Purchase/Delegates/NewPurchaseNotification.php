<?php

/**
 * Minds New Pledge Notification Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class NewPurchaseNotification
{
    /** @var Config */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function notify(Purchase $purchase)
    {
        $action = $this->config->get('blockchain')['sale'] == 'presale' ? 'pledge' : 'reservation';
        $pledgeData = $purchase->export();

        $message = "Your token {$action} for {$pledgeData['eth_amount']} ETH is awaiting review.";

        Dispatcher::trigger('notification', 'all', [
            'to' => [ $pledge->getUserGuid() ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 'message' => $message ],
            'message' => $message,
        ]);
    }
}
