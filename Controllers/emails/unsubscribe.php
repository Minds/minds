<?php
/**
 * Minds main page controller
 */
namespace Minds\Controllers\emails;

use Minds\Core;
use Minds\Interfaces;

class unsubscribe extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        \elgg_set_ignore_access();
        $username = strtolower($pages[0]);
        $user = new \Minds\Entities\User($username);

        if ($user->getEmail() == $pages[1]) {
            $user->disabled_emails = true;
            $user->save();
        }

        echo <<<HTML
    <h1>Thanks @$user->username. Come back soon!</h1>
    <strong>You have now been unsubscribed from future emails</strong>
HTML;
    }
    
    public function post($pages)
    {
    }
    
    public function put($pages)
    {
    }
    
    public function delete($pages)
    {
    }
}
