<?php

namespace Spec\Minds\Core\OAuth\Repositories;

use Minds\Core\OAuth\Repositories\ClientRepository;
use PhpSpec\ObjectBehavior;
use Minds\Core\Config;

class ClientRepositorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ClientRepository::class);
    }

    public function it_should_return_a_client_with_secret(
        Config $config
    ) {
        $this->beConstructedWith(null, $config);

        $config->get('checkout_url')->willReturn('checkout_url');

        $config->get('oauth')
            ->willReturn([
                'clients' => [
                    'browser' => [
                        'secret' => 'testsecret',
                    ],
                    'mobile' => [
                        'secret' => 'testsecret',
                    ],
                    'checkout' => [
                        'secret' => 'testsecret',
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

    public function it_should_not_return_a_client_with_wrong_secret(
        Config $config
    ) {
        $this->beConstructedWith(null, $config);

        $config->get('checkout_url')->willReturn('checkout_url');

        $config->get('oauth')
            ->willReturn([
                'clients' => [
                    'browser' => [
                        'secret' => 'testsecret',
                    ],
                    'mobile' => [
                        'secret' => 'testsecret',
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

    public function it_should_not_return_an_invalid_client()
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
