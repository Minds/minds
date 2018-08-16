<?php

/**
 * Minds New Purchase Email Delegate
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Util\BigNumber;
use Minds\Core\Email\Campaigns\Custom;
use Minds\Entities\User;

class NewPurchaseEmail
{
    /** @var Config */
    protected $config;

    /** @var Custom */
    protected $campaign;

    public function __construct($config = null, $campaign = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->campaign = $campaign ?: new Custom;
    }

    public function send(Purchase $purchase)
    {
        $amount = (int) BigNumber::_($purchase->getRequestedAmount())->div(10 ** 18)->toString();

        $this->campaign
            ->setUser(new User($purchase->getUserGuid()))
            ->setSubject("Your purchase of $amount Tokens is being processed.")
            ->setTemplate('new-token-purchase.md')
            ->setTopic('billing')
            ->setCampaign('tokens')
            ->setVars([
                'date' => date('d-M-Y', time()),
                'amount' => $amount,
            ])
            ->send();
    }
}
