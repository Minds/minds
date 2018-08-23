<?php
/**
 * Channels manager
 */
namespace Minds\Core\Channels;

class Manager
{

    /** @var User $user */
    protected $user;

    /** @var Delegates\DeleteUser */
    private $deleteUserDelegate;

    /** @var Delegates\DeleteArtifacts */
    private $deleteArtifactsDelegate;

    /** @var Delegates\Logout */
    private $logoutDelegate;

    public function __construct(
        $deleteUserDelegate = null,
        $deleteArtifactsDelegate = null,
        $logoutDelegate = null
    )
    {
        $this->deleteUserDelegate = $deleteUserDelegate ?: new Delegates\DeleteUser;
        $this->deleteArtifactsDelegate = $deleteArtifactsDelegate ?: new Delegates\DeleteArtifacts;
        $this->logoutDelegate = $logoutDelegate ?: new Delegates\Logout;
    }

    /**
     * Set the user to manage
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Deletes a channel
     * @return void
     */
    public function delete()
    {
        $this->deleteUserDelegate->delete($this->user);
        $this->deleteArtifactsDelegate->queue($this->user);
        $this->logoutDelegate->logout($this->user); //must be last as will logout
    }

}
