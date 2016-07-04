<?php
/**
 * Minds Translations API: Translate
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1\translation;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;

class translate implements Interfaces\Api
{
    /**
     * Translates an entity
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $target = null;

        if (isset($_GET['target']) && preg_match('/^[a-zA-Z0-9\-_]+$/', $_GET['target'])) {
            $target = $_GET['target'];
        }

        $translation = (object) (new Core\Translation\Translations())->translateEntity($pages[0], $target);

        $user = Core\Session::getLoggedInUser();
        $changed = (new Core\Translation\Languages())->changeUserLanguage($user, $target);

        if ($changed) {
            $user->save();
            $_SESSION['user'] = $user;
        }

        return Factory::response([
            'translation' => $translation,
            'purgeLanguagesCache' => $changed,
        ]);
    }

    /**
     * Not used
     */
    public function post($pages)
    {
        return Factory::response([]);
    }


    /**
     * Not used
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Not used
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
