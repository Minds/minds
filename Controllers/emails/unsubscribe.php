<?php
/**
 * Minds main page controller
 */
namespace Minds\Controllers\emails;

use Minds\Core;
use Minds\Entities\User;
use Minds\Interfaces;

class unsubscribe extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        \elgg_set_ignore_access();
        $campaign = strtolower($pages[2]);
        $topic = strtolower($pages[3]);
        $username = strtolower($pages[0]);
        $user = new User($username);

        if ($user->getEmail() == $pages[1]) {
            /** @var Core\Email\Manager $manager */
            $manager = Core\Di\Di::_()->get('Email\Manager');

            $manager->unsubscribe($user, [ $campaign ], [ $topic ]);
            $user->save();
        }

        $siteUrl= Core\Config::_()->site_url;

        echo <<<HTML
        <img src="https://d15u56mvtglc6v.cloudfront.net/front/public/assets/logos/medium-production.png" alt="Minds.com" align="middle" width="200px" height="80px"/>
    <h1 style="color:rgb(119, 119, 119);">SUCCESS. You won't receive any more emails like this to @$user->username</h1>
    <strong style="color:rgb(119, 119, 119);">You can also choose to stop receiving all email from Minds or adjust your other <a style="color:rgb(119, 119, 119);" href="{$siteUrl}settings/emails">email settings</a>.</strong>
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
