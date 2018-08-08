<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use Minds\Core\Blockchain\Purchase\Delegates\IssuedTokenNotification;
use Minds\Core\Blockchain\Purchase\Delegates\IssueTokens;
use Minds\Core\Blockchain\Purchase\Delegates\NewPurchaseNotification;
use Minds\Core\Blockchain\Purchase\Purchase as PurchaseModel;
use Minds\Core\Blockchain\Purchase\Repository;
use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Config;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


class ManagerSpec extends ObjectBehavior
{

    /** @var Repository */
    protected $repo;
    protected $txManager;
    protected $config;
    protected $issueTokens;
    protected $newPurchaseNotification;
    protected $issuedTokenNotification;

    function let(
        Repository $repo,
        Manager $txManager,
        Config $config,
        IssueTokens $issueTokens,
        NewPurchaseNotification $newPurchaseNotification,
        IssuedTokenNotification $issuedTokenNotification
    ) {
        $this->beConstructedWith($repo, $txManager, $config, $issueTokens, $newPurchaseNotification,
            $issuedTokenNotification);

        $this->repo = $repo;
        $this->txManager = $txManager;
        $this->config = $config;
        $this->issueTokens = $issueTokens;
        $this->newPurchaseNotification = $newPurchaseNotification;
        $this->issuedTokenNotification = $issuedTokenNotification;

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'token_sale_event' => [
                        'eth_rate' => 10,
                        'auto_issue_cap' => 100,
                        'contract_address' => '0xasdasd',
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Purchase\Manager');
    }

    function it_should_purchase()
    {
        $purchase = (new PurchaseModel())
            ->setPhoneNumberHash('0xhash')
            ->setUserGuid('123')
            ->setTx('0xasd')
            ->setRequestedAmount(BigNumber::toPlain(10 ** 18, 18))
            ->setTimestamp(time())
            ->setWalletAddress('0x123')
            ->setStatus('purchased');

        $this->txManager->add(Argument::type(Transaction::class))
            ->shouldBeCalled();

        $this->repo->add($purchase)
            ->shouldBeCalled();

        $this->purchase($purchase);
    }

    function it_should_issue_the_purchase()
    {
        $purchase = (new PurchaseModel())
            ->setPhoneNumberHash('0xhash')
            ->setUserGuid('123')
            ->setTx('0xasd')
            ->setRequestedAmount(BigNumber::toPlain(10 ** 18, 18))
            ->setTimestamp(time())
            ->setWalletAddress('0x123')
            ->setStatus('purchased');

        $this->repo->add($purchase)
            ->shouldBeCalled();
        $this->issuedTokenNotification->notify($purchase)
            ->shouldBeCalled();


        $this->issue($purchase)->shouldReturn(true);
    }

    function it_should_reject_the_purchase()
    {
        $purchase = (new PurchaseModel())
            ->setPhoneNumberHash('0xhash')
            ->setUserGuid('123')
            ->setTx('0xasd')
            ->setRequestedAmount(BigNumber::toPlain(10 ** 18, 18))
            ->setTimestamp(time())
            ->setWalletAddress('0x123')
            ->setStatus('purchased');

        $this->repo->add(Argument::that(function ($purchase) {
            return $purchase instanceof PurchaseModel && $purchase->getStatus() === 'rejected';
        }))->shouldBeCalled();

        $this->reject($purchase)->shouldReturn(true);
    }

    function it_should_get_the_auto_issue_cap()
    {
        $this->getAutoIssueCap()->shouldReturn(100);
    }

    function it_should_get_the_token_rate()
    {
        $this->getEthTokenRate()->shouldReturn(10);
    }
}
