<?php

/**
 * Minds Approved Pledge Email
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\PledgeApproval;
use Minds\Entities\User;

class ApprovedPurchaseEmail
{
    /** @var Config */
    protected $config;

    /** @var PledgeApproval */
    protected $campaign;

    public function __construct($config = null, $campaign = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->campaign = $campaign ?: new PledgeApproval();
    }

    public function send(Purchase $pledge)
    {
        $isPresale = $this->config->get('blockchain')['sale'] == 'presale';
        $pledgeData = $pledge->export();

        $this->campaign
            ->setUser(new User($pledge->getUserGuid()))
            ->setPresale($isPresale)
            ->setAmount($pledgeData['eth_amount'])
            ->send();
    }
}
