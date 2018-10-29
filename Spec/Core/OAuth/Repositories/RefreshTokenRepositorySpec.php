<?php

namespace Spec\Minds\Core\OAuth\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\OAuth\Repositories\RefreshTokenRepository;
use Minds\Core\Data\Cassandra\Client;
use Minds\Core\OAuth\Entities\AccessTokenEntity;
use Minds\Core\OAuth\Entities\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;

use Cassandra\Timestamp;

class RefreshTokenRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RefreshTokenRepository::class);
    }

    function it_should_save_refresh_token(
        Client $client,
        RefreshTokenEntity $refreshTokenEntity,
        AccessTokenEntity $accessTokenEntity
    )
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1'
                ;
                //&& $values[1] === 'access_token_1'
                //&& $values[2] === new Timestamp(strtotime('25th December 2018'));
            }))
            ->shouldBeCalled();

        $refreshTokenEntity->getIdentifier()
            ->willReturn('id_1');

        $refreshTokenEntity->getAccessToken()
            ->willReturn($accessTokenEntity);

        $accessTokenEntity->getIdentifier()
            ->willReturn('access_token_1');

        $refreshTokenEntity->getExpiryDateTime()
            ->willReturn(new \DateTime('25th December 2018'));
    
        $this->persistNewRefreshToken($refreshTokenEntity);
    }

    function it_should_revoke_refresh_token(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled();

        $this->revokeRefreshToken('id_1');
    }

    function it_should_return_refresh_token_is_revoked(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled()
            ->willReturn(null);

        $this->isRefreshTokenRevoked('id_1')
            ->shouldBe(true);
    }

    function it_should_return_refresh_token_is_not_revoked(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled()
            ->willReturn([ 'token_id' => 'id_1' ]);

        $this->isRefreshTokenRevoked('id_1')
            ->shouldBe(false);
    }

    function it_should_return_an_refresh_token(
        ClientEntityInterface $clientEntity
    )
    {
        $refreshToken = $this->getNewRefreshToken();
        $refreshToken->shouldNotBeNull();
    }

}
