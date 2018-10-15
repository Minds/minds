<?php

namespace Spec\Minds\Core\OAuth\Repositories;

use Minds\Core\OAuth\Repositories\UserRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;

class UserRepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(UserRepository::class);
    }

    function it_should_return_a_user_with_credentials(
        ClientEntityInterface $clientEntity
    )
    {
        $this->mockUser = new User;
        $this->mockUser->guid = 123;
        $this->mockUser->password = password_hash('testpassword', PASSWORD_BCRYPT);

        $userEntity = $this->getUserEntityByUserCredentials(
            'spec-user-test', 
            'testpassword',
            'password',
            $clientEntity
        );

        $userEntity->getIdentifier()
            ->shouldReturn(123);
    }

    function it_should_not_return_a_user_with_bad_credentials(
        ClientEntityInterface $clientEntity
    )
    {
        $this->mockUser = new User;
        $this->mockUser->guid = 123;
        $this->mockUser->password = password_hash('testpassword', PASSWORD_BCRYPT);

        $userEntity = $this->getUserEntityByUserCredentials(
            'spec-user-test', 
            'wrongtestpassword',
            'password',
            $clientEntity
        );

        $userEntity->shouldReturn(false);
    }

}
