<?php

/**
 * Minds Approved Pledge Notification
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Pledges\Delegates;

use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class ApprovedPledgeNotification
{
    /** @var Config */
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function notify(Pledge $pledge)
    {
        $this->sendInternalNotification($pledge);
        $this->sendEmail($pledge);
    }

    protected function sendInternalNotification(Pledge $pledge)
    {
        $isPresale = $this->config->get('blockchain')['sale'] == 'presale';
        $action = $isPresale ? 'pledge' : 'reservation';
        $pledgeData = $pledge->export();

        $message = "Your token {$action} for {$pledgeData['eth_amount']} ETH was approved.";

        if ($isPresale) {
            $message .= " You will be able to buy your tokens as soon as the sale starts.";
        } else {
            $message .= " You can buy your tokens now at The Minds Token page.";
        }

        Dispatcher::trigger('notification', 'all', [
            'to' => [ $pledge->getUserGuid() ],
            'from' => 100000000000000519,
            'notification_view' => 'custom_message',
            'params' => [ 'message' => $message, 'router_link' => '/token' ],
            'message' => $message,
        ]);
    }

    protected function sendEmail(Pledge $pledge)
    {
        // TODO: Send email
    }
}
