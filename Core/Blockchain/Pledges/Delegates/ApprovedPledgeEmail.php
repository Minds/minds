<?php

/**
 * Minds Approved Pledge Email
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Pledges\Delegates;

use Minds\Core\Blockchain\Pledges\Pledge;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\PledgeApproval;
use Minds\Entities\User;

class ApprovedPledgeEmail
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

    public function send(Pledge $pledge)
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
