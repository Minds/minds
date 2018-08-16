<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use Minds\Core\Blockchain\Purchase\Delegates;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Blockchain\Purchase\Repository;
use Minds\Core\Config\Config;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    /** @var Repository $repo */
    private $repo;

    /** @var \Minds\Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var Config */
    protected $config;

    /** @var Delegates\IssueTokens */
    private $issueTokens;

    /** @var Delegates\NewPurchaseNotification */
    private $newPurchaseNotification;


    /** @var Delegates\IssuedTokenNotification */
    private $issuedTokenNotification;

    /** @var Delegates\IssuedTokenEmail */
    private $issuedTokenEmail;

    /** @var Delegates\NewPurchaseEmail */
    private $newPurchaseEmail;

    function let(
        Repository $repo,
        \Minds\Core\Blockchain\Transactions\Manager $txManager,
        Config $config,
        Delegates\IssueTokens $issueTokens,
        Delegates\NewPurchaseNotification $newPurchaseNotification,
        Delegates\IssuedTokenNotification $issuedTokenNotification,
        Delegates\IssuedTokenEmail $issuedTokenEmail,
        Delegates\NewPurchaseEmail $newPurchaseEmail
    ) {
        $this->beConstructedWith($repo, $txManager, $config, $issueTokens, $newPurchaseNotification,
            $issuedTokenNotification, $issuedTokenEmail, $newPurchaseEmail);

        $this->repo = $repo;
        $this->txManager = $txManager;
        $this->config = $config;
        $this->issueTokens = $issueTokens;
        $this->newPurchaseNotification = $newPurchaseNotification;
        $this->issuedTokenNotification = $issuedTokenNotification;
        $this->issuedTokenEmail = $issuedTokenEmail;
        $this->newPurchaseEmail = $newPurchaseEmail;

        $this->config->get('blockchain')
            ->willReturn([
                'contracts' => [
                    'token_sale_event' => [
                        'contract_address' => '0x123',
                        'auto_issue_cap' => 100,
                        'eth_rate' => 1
                    ]
                ]
            ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Purchase\Manager');
    }

    function it_should_get_auto_issue_cap()
    {
        $this->getAutoIssueCap()->shouldReturn(100);
    }

    function it_should_get_eth_token_rate()
    {
        $this->getEthTokenRate()->shouldReturn(1);
    }

    function it_should_get_a_purchase(Purchase $purchase)
    {
        $this->repo->get('hash', '0x123123')
            ->shouldBeCalled()
            ->willReturn($purchase);

        $this->getPurchase('hash', '0x123123')->shouldReturnAnInstanceOf(Purchase::class);
    }

    function it_should_register_a_purchase_transaction(Purchase $purchase)
    {
        $purchase->getTx()
            ->shouldBeCalled()
            ->willReturn('0x123123');
        $purchase->getRequestedAmount()
            ->shouldBeCalled()
            ->willReturn(100);
        $purchase->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0x123');
        $purchase->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(12345678);
        $purchase->getUserGuid()
            ->shouldBeCalled()
            ->willReturn('123');
        $purchase->getPhoneNumberHash()
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->txManager->add(Argument::type('Minds\Core\Blockchain\Transactions\Transaction'))
            ->shouldBeCalled()
            ->willReturn($purchase);

        $this->repo->add($purchase)
            ->shouldBeCalled();

        $this->newPurchaseNotification->notify($purchase)
            ->shouldBeCalled();

        $this->newPurchaseEmail->send($purchase)
            ->shouldBeCalled();

        $this->purchase($purchase);
    }

    function it_should_add_a_purchase_to_the_database(Purchase $purchase)
    {
        $this->repo->add($purchase)
            ->shouldBeCalled();

        $this->add($purchase);
    }

    function it_should_issue_a_purchase(Purchase $purchase)
    {
        $this->issueTokens->issue($purchase)
            ->shouldBeCalled();

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(100);
        $purchase->setIssuedAmount(100)
            ->shouldBeCalled();
        $purchase->setStatus('issued')
            ->shouldBeCalled();

        $this->repo->add($purchase)
            ->shouldBeCalled();

        $this->issuedTokenNotification->notify($purchase)
            ->shouldBeCalled();

        $this->issuedTokenEmail->send($purchase)
            ->shouldBeCalled();

        $this->issue($purchase);
    }

    function it_should_reject_a_purchase(Purchase $purchase)
    {
        $purchase->setStatus('rejected')
            ->shouldBeCalled();

        $this->repo->add($purchase)
            ->shouldBeCalled();

        $this->reject($purchase);
    }
}
