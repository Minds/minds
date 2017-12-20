<?php

namespace Spec\Minds\Core\Blockchain\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Http\Curl\JsonRpc\Client as JsonRpc;
use Minds\Core\Config;

class EthereumSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Services\Ethereum');
    }

    function it_should_request_to_ethereum(Config $config, JsonRpc $jsonRpc)
    {
        $this->beConstructedWith($config, $jsonRpc);

        $config->get('blockchain')->willReturn([
            'rpc_endpoints' => [ '127.0.0.1' ],
            'mw3' => '/dev/null'
        ]);

        $jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_test',
            'params' => []
        ])->willReturn([ 'result' => [ 'foo' => 'bar' ]]);

        $this->request('eth_test')->shouldReturn([ 'foo' => 'bar']);
    }

    function it_should_throw_exception_on_error_request(Config $config, JsonRpc $jsonRpc)
    {
        $this->beConstructedWith($config, $jsonRpc);

        $config->get('blockchain')->willReturn([
            'rpc_endpoints' => [ '127.0.0.1' ],
            'mw3' => '/dev/null'
        ]);

        $jsonRpc->post(Argument::type('string'), [
            'method' => 'eth_err',
            'params' => []
        ])->willReturn([ 'error' => 'Testing' ]);

        $this->shouldThrow('\Exception')->duringRequest('eth_err');
    }

    function it_should_return_sha3_from_string(Config $config, JsonRpc $jsonRpc)
    {
        $this->beConstructedWith($config, $jsonRpc);
        
        $config->get('blockchain')->willReturn([
            'rpc_endpoints' => [ '127.0.0.1' ],
            'mw3' => '/dev/null'
        ]);

        $jsonRpc->post(Argument::type('string'), Argument::type('array'))
            ->willReturn([ 'result' => '00hello' ]);

        $this->sha3("hello")->shouldReturn("hello");
    }

    /*function it_should_call_request(Config $config, JsonRpc $jsonRpc)
    {
        $this->beConstructedWith($config, $jsonRpc);
        
        $config->get('blockchain')->willReturn([
            'rpc_endpoints' => [ '127.0.0.1' ]
        ]);

        $jsonRpc->post('127.0.0.1',
            [
                'method' => 'web3_sha3',
                'params' => [
                    '0x636f6e74726163744d6574686f644465636c61726174696'
                ] 
            ])
        ->shouldBeCalledTimes(1)
        ->willReturn([ 'result' => "sig" ]);

        $jsonRpc->post(Argument::type('string'),
            [
                'method' => 'eth_call',
                'params' => [
                    [
                        'to' => 'contractAddr',
                        'data' => '0xsig'
                    ],
                    'latest'
                    ] 
                ])
            ->shouldBeCalledTimes(1)
            ->willReturn([ 'result' => "returned" ]);

        $this->call('contractAddr', 'contractMethodDeclaration', [])
            ->shouldReturn("returned");
    }*/
}
