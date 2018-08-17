<?php

namespace Spec\Minds\Core\Blockchain\Services;

use Minds\Core\Blockchain\Config;
use Minds\Core\Blockchain\GasPrice;
use Minds\Core\Http\Curl\JsonRpc\Client as JsonRpc;
use Minds\Core\Util\BigNumber;
use MW3\Sha3;
use MW3\Sign;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EthereumSpec extends ObjectBehavior
{
    private $_config;
    private $_jsonRpc;
    private $_sign;
    private $_sha3;
    private $_gasPrice;

    function let(Config $config, JsonRpc $jsonRpc, Sign $sign, Sha3 $sha, GasPrice $gasPrice)
    {
        $this->_config = $config;
        $this->_jsonRpc = $jsonRpc;
        $this->_sign = $sign;
        $this->_sha3 = $sha;
        $this->_gasPrice = $gasPrice;

        $this->beConstructedWith($config, $jsonRpc, $sign, $sha, $gasPrice);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Services\Ethereum');
    }

    function it_should_request_to_ethereum()
    {
        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null'
        ]);

        $this->_jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_test',
            'params' => []
        ])->willReturn(['result' => ['foo' => 'bar']]);

        $this->request('eth_test')->shouldReturn(['foo' => 'bar']);
    }

    function it_should_throw_exception_on_error_request()
    {
        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null'
        ]);

        $this->_jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_err',
            'params' => []
        ])->willReturn([
            'error' => [
                'code' => '100',
                'message' => 'Testing'
            ]
        ]);

        $this->shouldThrow(new \Exception('[Ethereum] 100: Testing'))->duringRequest('eth_err');
    }

    function it_should_throw_exception_when_there_is_no_request()
    {
        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null'
        ]);

        $this->_jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_err',
            'params' => []
        ])->willReturn(null);

        $this->shouldThrow(new \Exception('Server did not respond'))->duringRequest('eth_err');
    }

    function it_should_return_sha3_from_string()
    {
        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null'
        ]);

        $this->_jsonRpc->post(Argument::type('string'), Argument::type('array'))
            ->willReturn(['result' => '00hello']);

        $this->_sha3->setString("hello")->shouldBeCalled()->willReturn($this->_sha3);
        $this->_sha3->hash()->willReturn('hello');

        $this->sha3("hello")->shouldReturn("hello");
    }

    function it_should_change_the_current_config()
    {
        $this->_config->setKey('mainnet')
            ->shouldBeCalled();

        $this->useConfig('mainnet')->shouldReturn($this->getWrappedObject());
    }

    function it_should_encode_a_contract_method()
    {
        $this->_sha3->setString('issue(address,uint256)')
            ->shouldBeCalled()
            ->willReturn($this->_sha3);

        $this->_sha3->hash()
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->encodeContractMethod('issue(address,uint256)', ['0x123', BigNumber::_(10 ** 18)->toHex(true)])
            ->shouldReturn('0xhash00000000000000000000000000000000000000000000000000000000000001230000000000000000000000000000000000000000000000000de0b6b3a7640000');
    }

    function it_should_fail_to_encode_a_contract_method_because_of_a_non_hex_param()
    {
        $this->_sha3->setString('issue(address,uint256)')
            ->shouldBeCalled()
            ->willReturn($this->_sha3);

        $this->_sha3->hash()
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->shouldThrow(new \Exception('Ethereum::call only supports raw hex parameters'))
            ->during('encodeContractMethod',
                ['issue(address,uint256)', ['123', BigNumber::_(10 ** 18)->toHex(true)]]);
    }

    function it_should_run_a_raw_method_unsigned_call()
    {
        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null'
        ]);

        $this->_jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_call',
            'params' => [
                [
                    'to' => '0x123',
                    'data' => '0xhash'
                ],
                'latest'
            ]
        ])->willReturn(['result' => ['foo' => 'bar']]);

        $this->_sha3->setString('method()')
            ->shouldBeCalled()
            ->willReturn($this->_sha3);

        $this->_sha3->hash()
            ->shouldBeCalled()
            ->willReturn('hash');

        $this->call('0x123', 'method()', [])->shouldReturn(['foo' => 'bar']);
    }

    function it_should_sign_a_transaction()
    {
        $transaction = [];
        $this->_sign->setPrivateKey('privateKey')
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_sign->setTx(json_encode($transaction))
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_sign->sign()
            ->shouldBeCalled()
            ->willReturn('signed');

        $this->sign('privateKey', $transaction)->shouldReturn('signed');
    }

    function it_should_send_a_raw_transaction()
    {
        $transaction = [
            'from' => '0x123',
            'gasLimit' => '1000',
            'nonce' => 'nonce'
        ];

        $this->_gasPrice->getLatestGasPrice(Argument::any())
            ->shouldBeCalled()
            ->willReturn('0x2540be400');

        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null',
            'server_gas_price' => 100,
        ]);

        $this->_sign->setPrivateKey('privateKey')
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_sign->setTx(json_encode(array_merge($transaction, ['gasPrice' => '0x2540be400'])))
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_sign->sign()
            ->shouldBeCalled()
            ->willReturn('signed');

        $this->_jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_sendRawTransaction',
            'params' => ['signed']
        ])->willReturn(['result' => ['foo' => 'bar']]);

        $this->sendRawTransaction('privateKey', $transaction)->shouldReturn(['foo' => 'bar']);
    }

    function it_should_fail_when_sending_raw_transaction_because_theres_no_from_param()
    {
        $transaction = [
            'gasLimit' => '1000',
            'nonce' => 'nonce'
        ];

        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null',
            'server_gas_price' => 100,
        ]);

        $this->shouldThrow(new \Exception('Transaction must have `from` and `gasLimit`'))->during('sendRawTransaction',
            ['privateKey', $transaction]);
    }

    function it_should_fail_when_sending_raw_transaction_because_theres_no_from_gasLimit()
    {
        $transaction = [
            'from' => '0x123',
            'nonce' => 'nonce'
        ];

        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null',
            'server_gas_price' => 100,
        ]);

        $this->shouldThrow(new \Exception('Transaction must have `from` and `gasLimit`'))->during('sendRawTransaction',
            ['privateKey', $transaction]);
    }

    function it_should_fail_when_sending_raw_transaction_because_theres_an_error_signing_the_transaction()
    {
        $transaction = [
            'from' => '0x123',
            'gasLimit' => '1000',
            'nonce' => 'nonce'
        ];

        $this->_config->get()->willReturn([
            'rpc_endpoints' => ['127.0.0.1'],
            'mw3' => '/dev/null',
            'server_gas_price' => 100,
        ]);

        $this->_sign->setPrivateKey('privateKey')
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_gasPrice->getLatestGasPrice(Argument::any())
            ->shouldBeCalled()
            ->willReturn('0x2540be400');

        $this->_sign->setTx(json_encode(array_merge($transaction, ['gasPrice' => '0x2540be400'])))
            ->shouldBeCalled()
            ->willReturn($this->_sign);

        $this->_sign->sign()
            ->shouldBeCalled()
            ->willReturn('');

        $this->shouldThrow(new \Exception('Error signing transaction'))->during('sendRawTransaction',
            ['privateKey', $transaction]);
    }
}
