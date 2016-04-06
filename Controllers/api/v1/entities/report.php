<?php
/**
 * Minds Entity Report API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\entities;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class report implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (count($pages) < 1) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);
        $user = Core\Session::getLoggedInUser();
        $subject = $_POST['subject'];

        if (!$entity || !$subject || !$user) {
            return Factory::response([]);
        }

        $done = (new Core\Reports())->insert($entity, $user, $subject);

        return Factory::response([
            'done' => $done
        ]);
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
