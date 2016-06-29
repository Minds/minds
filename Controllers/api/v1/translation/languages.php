<?php
/**
 * Minds Translations API: Languages list
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1\translation;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;

class languages implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Get the list of supported languages
     */
    public function get($pages)
    {
        $target = null;

        if (isset($_GET['target']) && preg_match('/^[a-zA-Z0-9\-_]+$/', $_GET['target'])) {
            $target = $_GET['target'];
        }

        return Factory::response([
            'languages' => (new Core\Translation\Languages())->getLanguages($target)
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
