<?php
/**
 * Minds OAuth UserRepository
 */
namespace Minds\Core\OAuth\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Minds\Core\OAuth\Entities\UserEntity;
use Minds\Entities\User;
use Minds\Core\Di\Di;

class UserRepository implements UserRepositoryInterface
{
    /** @var Password $password */
    private $password;

    /** @var User $mock */
    public $mockUser = false;

    public function __construct(Password $password = null)
    {
        $this->password = $password ?: Di::_()->get('Security\Password');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        if (!$username || !$password) {
            return false;
        }

        if ($this->mockUser) {
            $user = $this->mockUser;
        } else {
            $user = new User(strtolower($username));
        }

        if (!$user->getGuid()) {
            return false;
        }

        if (!$this->password->check($user, $password)) {
            return false;
        }

        $entity = new UserEntity();
        $entity->setIdentifier($user->getGuid());
        return $entity;
    }
}