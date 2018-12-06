<?php
/**
 * Minds Session Manager
 */
namespace Minds\Core\Sessions;

use Minds\Common\Cookie;
use Minds\Core;
use Minds\Core\Di\Di;
use Lcobucci\JWT;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha512;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var Config $config */
    private $config;

    /** @var Cookie $cookie */
    private $cookie;

    /** @var Session $session */
    private $session;

    /** @var User $user */
    private $user;

    public function __construct(
        $repository = null,
        $config = null,
        $cookie = null,
        $jwtBuilder = null,
        $jwtParser = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->config = $config ?: Di::_()->get('Config');
        $this->cookie = $cookie ?: new Cookie;
        $this->jwtBuilder = $jwtBuilder ?: new JWT\Builder;
        $this->jwtParser = $jwtParser ?: new JWT\Parser;
    }

    /**
     * Set the session
     * @param Session $session
     * @return $this
     */
    public function setSession($session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * Return the current session
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /** 
     * Set the user for the session
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $user;
    }

    /**
     * Build session from jwt cookie
     * @return $this
     */
    public function withRouterRequest($request)
    {
        $cookies = $request->getCookieParams();
        if (!isset($cookies['minds_sess'])) {
            return $this;
        }

        try {
            $token = $this->jwtParser->parse((string) $cookies['minds_sess']); // Collect from cookie
            $token->getHeaders();
            $token->getClaims();
        } catch (\Exception $e) {
            return $this;
        }

        $key = $this->config->get('sessions')['public_key'];

        if (!$token->verify(new Sha512, $key)) {
            return $this;
        }

        $id = $token->getHeader('jti');
        $user_guid = $token->getClaim('user_guid');
        $expires = $token->getClaim('exp');

        $session = new Session;
        $session
            ->setId($id)
            ->setUserGuid($user_guid)
            ->setToken($token)
            ->setExpires($expires);

        if (!$this->validateSession($session)) {
            return $this;
        }

        $this->session = $session;

        // Sets the global user
        Core\Session::setUserByGuid($user_guid);

        // Generate JWT cookie for sockets
        // Hack, needs refactoring
        Core\Session::generateJWTCookie($session);

        return $this;
    }

    /**
     * Validate the session
     * @param Session $session
     * @return bool
     */
    public function validateSession($session)
    {
        $validated = $this->repository->get(
            $session->getUserGuid(),
            $session->getId()
        );

        if (!$validated) {
            return false;
        }

        if (
            !$session->getId() 
            || $session->getId() != $validated->getId()
        ) {
            return false;
        }

        if (
            !$session->getUserGuid()
            || $session->getUserGuid() != $validated->getUserGuid()
        ) {
            return false;
        }

        if (
            !$session->getExpires()
            || $session->getExpires() != $validated->getExpires()
            || $session->getExpires() < time()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Create the session
     * @return $this
     */
    public function createSession()
    {
        $id = $this->generateId();
        $expires = time() + (60 * 60 * 24 * 30); // 30 days

        $token = $this->jwtBuilder
                    //->issuedBy($this->config->get('site_url'))
                    //->canOnlyBeUsedBy($this->config->get('site_url'))
                    ->setId($id, true)
                    ->setExpiration($expires)
                    ->set('user_guid', (string) $this->user->getGuid())
                    ->sign(new Sha512, $this->config->get('sessions')['private_key'])
                    ->getToken();

        $this->session = new Session();
        $this->session
            ->setId($id)
            ->setToken($token)
            ->setUserGuid($this->user->getGuid())
            ->setExpires($expires);
            
        return $this;
    }

    private function generateId()
    {
        $bytes = openssl_random_pseudo_bytes(128);
        return hash('sha512', $bytes);
    }

    /**
     * Save the session to the database and client
     * @return $this
     */
    public function save()
    {
        $this->repository->add($this->session);

        $this->cookie
            ->setName('minds_sess')
            ->setValue($this->session->getToken())
            ->setExpire($this->session->getExpires())
            ->setSecure(true) //only via ssl
            ->setHttpOnly(true) //never by browser
            ->setPath('/')
            ->create();

        return $this;
    }

    /**
     * Remove the session from the database and client
     * @return $this
     */
    public function destroy($all = false)
    {
        $this->repository->delete($this->session, $all);
               
        $this->cookie
            ->setName('minds_sess')
            ->setValue('')
            ->setExpire(time() - 3600)
            ->setSecure(true) //only via ssl
            ->setHttpOnly(true) //never by browser
            ->setPath('/')
            ->create();
    }

    /**
     * Return the count of active sessions
     * @return int
     */
    public function getActiveCount()
    {
        return $this->repository->getCount($this->user->getGuid());
    }

}
