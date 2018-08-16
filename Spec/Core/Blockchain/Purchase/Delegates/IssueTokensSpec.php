<?php

namespace Spec\Minds\Core\Blockchain\Purchase\Delegates;

use Minds\Core\Blockchain\Config;
use Minds\Core\Blockchain\Purchase\Delegates\IssueTokens;
use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Blockchain\Transactions\Repository;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;

class IssueTokensSpec extends ObjectBehavior
{
    /** @var Config */
    private $config;

    /** @var Ethereum */
    private $client;

    /** @var Repository */
    private $repository;

    function let(Config $config, Ethereum $client, Repository $repository)
    {
        $this->config = $config;
        $this->client = $client;
        $this->repository = $repository;

        $this->beConstructedWith($config, $client, $repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(IssueTokens::class);
    }

    function it_shuold_issue_a_purchase(Purchase $purchase)
    {
        $this->client->useConfig('pledge')
            ->shouldBeCalled();

        $this->config->setKey('pledge')
            ->shouldBeCalled();

        $this->config->get()
            ->shouldBeCalled()
            ->willReturn([
                'contracts' => [
                    'token_sale_event' => [
                        'wallet_pkey' => '0xKEY',
                        'wallet_address' => '0xWALLET',
                        'contract_address' => '0xCONTRACT'
                    ]
                ]
            ]);

        $purchase->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0xADDRESS');

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(100);

        $this->client->encodeContractMethod('issue(address,uint256)', [
            '0xADDRESS',
            BigNumber::_(100)->toHex(true)
        ])
            ->shouldBeCalled()
            ->willReturn('encoded');

        $this->client->sendRawTransaction('0xKEY', [
            'from' => '0xWALLET',
            'to' => '0xCONTRACT',
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => 'encoded'
        ])
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->issue($purchase);
    }

    function it_shuold_issue_a_purchase_but_fail(Purchase $purchase)
    {
        $this->client->useConfig('pledge')
            ->shouldBeCalled();

        $this->config->setKey('pledge')
            ->shouldBeCalled();

        $this->config->get()
            ->shouldBeCalled()
            ->willReturn([
                'contracts' => [
                    'token_sale_event' => [
                        'wallet_pkey' => '0xKEY',
                        'wallet_address' => '0xWALLET',
                        'contract_address' => '0xCONTRACT'
                    ]
                ]
            ]);

        $purchase->getWalletAddress()
            ->shouldBeCalled()
            ->willReturn('0xADDRESS');

        $purchase->getUnissuedAmount()
            ->shouldBeCalled()
            ->willReturn(100);

        $this->client->encodeContractMethod('issue(address,uint256)', [
            '0xADDRESS',
            BigNumber::_(100)->toHex(true)
        ])
            ->shouldBeCalled()
            ->willReturn('encoded');

        $this->client->sendRawTransaction('0xKEY', [
            'from' => '0xWALLET',
            'to' => '0xCONTRACT',
            'gasLimit' => BigNumber::_(200000)->toHex(true),
            'data' => 'encoded'
        ])
            ->shouldBeCalled()
            ->willReturn('');

        $this->shouldThrow(new \Exception('Cannot retrieve Blockchain Tx address'))->during('issue', [$purchase]);
    }
}
