<?php

namespace Spec\Minds\Core\OAuth\Repositories;

use Minds\Core\OAuth\Repositories\ClientRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Config;

class ClientRepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(ClientRepository::class);
    }

    function it_should_return_a_client_with_secret(
        Config $config
    )
    {
        $this->beConstructedWith(null, $config);

        $config->get('oauth')
            ->willReturn([
                'clients' => [
                    'browser' => [
                        'secret' => 'testsecret'
                    ],
                    'mobile' => [
                        'secret' => 'testsecret'
                    ],
                ],
            ]);

        $client = $this->getClientEntity(
            'mobile',
            'password',
            'testsecret',
            true
        );

        $client->getIdentifier()
            ->shouldReturn('mobile');
    }

    function it_should_not_return_a_client_with_wrong_secret(
        Config $config
    )
    {
        $this->beConstructedWith(null, $config);

        $config->get('oauth')
            ->willReturn([
                'clients' => [
                    'browser' => [
                        'secret' => 'testsecret'
                    ],
                    'mobile' => [
                        'secret' => 'testsecret'
                    ],
                ],
            ]);

        $client = $this->getClientEntity(
            'browser',
            'password',
            'wrongtestsecret',
            true
        );

        $client->shouldReturn(null);
    }

    function it_should_not_return_an_invalid_client()
    {
        $client = $this->getClientEntity(
            'invalid',
            'password',
            null,
            false
        );

        $client->shouldReturn(null);
    }

}
