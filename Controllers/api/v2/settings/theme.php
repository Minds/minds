<?php
/**
 * API for user theme preference
 *
 * @version 1
 * @author Olivia Madrid
 */
namespace Minds\Controllers\api\v2\settings;

use Minds\Api\Factory;
use Minds\Core\Config;
use Minds\Core\Session;
use Minds\Interfaces;

class theme implements Interfaces\Api
{
  
    public function get($pages)
    {
        $user = Session::getLoggedInUser();

        return Factory::response([
            'theme' => $user->getTheme(),
        ]);
    }

    public function post($pages)
    {
        $user = Session::getLoggedInUser();
        
        $user->setTheme($_POST['theme']);
        $user->save();
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }  
}


