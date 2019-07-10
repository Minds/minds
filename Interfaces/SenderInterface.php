<?php
namespace Minds\Interfaces;

use Minds\Entities\User;

/** 
 * Delegate interface for sending emails
*/

interface SenderInterface
{

    /**
     * sending campaign emails to a user
     * @return void
     */
    public function send(User $user);

}