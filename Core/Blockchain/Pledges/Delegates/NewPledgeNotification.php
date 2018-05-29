<?php

/**
 * Minds New Pledge Notification Delegate
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Pledges\Delegates;

use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class NewPledgeNotification
{
    /** @var Config */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function notify(Pledge $pledge)
    {
        $action = $this->config->get('blockchain')['sale'] == 'presale' ? 'pledge' : 'reservation';
        $pledgeData = $pledge->export();

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
