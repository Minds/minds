<?php

namespace Spec\Minds\Core\OAuth\Repositories;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\OAuth\Repositories\AccessTokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Timestamp;
use Cassandra\Varint;

class AccessTokenRepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(AccessTokenRepository::class);
    }

    function it_should_save_access_token(
        Client $client,
        AccessTokenEntityInterface $accessTokenEntity,
        ClientEntityInterface $clientEntity
    )
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1'
                && $values[1] === 'client_1'
                //&& $values[2] === new Varint(123)
                //&& $values[3] === new Timestamp(strtotime('25th December 2018'))
                //&& $values[4] === new Timestamp(time())
                ;
                //&& $values[5] === new Set();
            }))
            ->shouldBeCalled();

        $accessTokenEntity->getIdentifier()
            ->willReturn('id_1');

        $accessTokenEntity->getClient()
            ->willReturn($clientEntity);

        $clientEntity->getIdentifier()
            ->willReturn('client_1');

        $accessTokenEntity->getUserIdentifier()
            ->willReturn(123);

        $accessTokenEntity->getExpiryDateTime()
            ->willReturn(new \DateTime('25th December 2018'));

        $accessTokenEntity->getScopes()
            ->willReturn([]);

        $this->persistNewAccessToken($accessTokenEntity);
    }

    function it_should_revoke_access_token(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled();

        $this->revokeAccessToken('id_1');
    }

    function it_should_return_access_token_is_revoked(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled()
            ->willReturn(false);

        $this->isAccessTokenRevoked('id_1')
            ->shouldBe(true);
    }

    function it_should_return_access_token_is_not_revoked(Client $client)
    {
        $this->beConstructedWith($client);

        $client->request(Argument::that(function($prepared) {
            $query = $prepared->build();

            $template = $query['string'];
            $values = $query['values'];

            return $values[0] === 'id_1';
            }))
            ->shouldBeCalled()
            ->willReturn([
                [ 'token_id' => 'id_1' ]
            ]);

        $this->isAccessTokenRevoked('id_1')
            ->shouldBe(false);
    }

    function it_should_return_an_access_token(
        ClientEntityInterface $clientEntity
    )
    {
        $clientEntity->getIdentifier()
            ->willReturn('client_1');

        $accessToken = $this->getNewToken($clientEntity, [], 'user_1');

        $accessToken
            ->getClient()
            ->getIdentifier()
            ->shouldBe('client_1');

        $accessToken
            ->getUserIdentifier()
            ->shouldBe('user_1');
    }

}
