<?php
/**
 * Minds Settings API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class settings implements Interfaces\Api
{
    /**
     * Extended channel
     *
     * @SWG\GET(
     *     summary="Return settings",
     *     path="/v1/settings",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        if (Core\Session::getLoggedInUser()->isAdmin() && isset($pages[0])) {
            $user = new entities\User($pages[0]);
        } else {
            $user = Core\Session::getLoggedInUser();
        }

        $response = array();

        $response['channel'] = $user->export();
        $response['channel']['email'] = $user->getEmail();
        $response['channel']['boost_rating'] = $user->getBoostRating();

        $response['thirdpartynetworks'] = Core\Di\Di::_()->get('ThirdPartyNetworks\Manager')->status();

        return Factory::response($response);
    }

    /**
     * Registers a user
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Update settings",
     *     path="/v1/settings",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        Factory::isLoggedIn();

        if (!Core\Security\XSRF::validateRequest()) {
            //return false;
        }

        if (Core\Session::getLoggedInUser()->isAdmin() && isset($pages[0])) {
            $user = new entities\User($pages[0]);
        } else {
            $user = Core\Session::getLoggedInUser();
        }

        if (isset($_POST['name']) && $_POST['name']) {
            $user->name = $_POST['name'];
        }

        if (isset($_POST['email']) && $_POST['email']) {
            $user->setEmail($_POST['email']);
        }

        if (isset($_POST['boost_rating'])) {
            $user->setBoostRating((int) $_POST['boost_rating']);
        }

        if (isset($_POST['mature'])) {
            $user->setMature(isset($_POST['mature']) && (int) $_POST['mature']);
        }

        if (isset($_POST['monetized']) && $_POST['monetized']) {
            $user->monetized = $_POST['monetized'];
        }

        if (isset($_POST['password']) && $_POST['password']) {
            if (!Core\Security\Password::check($user, $_POST['password'])) {
                return Factory::response(array(
                  'status' => 'error',
                  'message' => 'You current password is incorrect'
                ));
            }
            //need to create a new salt and hash...
            $user->salt = Core\Security\Password::salt();
            $user->password = Core\Security\Password::generate($user, $_POST['new_password']);
            $user->override_password = true;
        }

        $allowedLanguages = ['en', 'es', 'fr'];

        if (isset($_POST['language']) && in_array($_POST['language'], $allowedLanguages)) {
            $user->setLanguage($_POST['language']);
        }

        $response = array();
        if (!$user->save()) {
            //update or session
            if ($user->getGuid() == Core\Session::getLoggedInUser()->getGuid()) {
                $_SESSION['user'] = $user;
            }

            $response['status'] = 'error';
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
