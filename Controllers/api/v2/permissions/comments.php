<?php

namespace Minds\Controllers\api\v2\permissions;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Session; 
use Minds\Core\Permissions\Permissions; 

class comments implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Entity guid must be provided',
            ]);
        }
    }

    public function post($pages)
    {
        Factory::isLoggedIn();
        $owner = Session::getLoggedInUser();
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Entity guid must be provided',
            ]);
        }
        if (!isset($_POST['allowed'])) {
            return Factory::response([
                'status' => 'error',
                'message' => '(bool) allowed must be provided',
            ]);
        }

        $allowed = (bool) $_POST['allowed'];

        /** @var EntitiesBuilder $entitiesBuilder */
        $entitiesBuilder = Di::_()->get('EntitiesBuilder');
        $entity = $entitiesBuilder->single($pages[0]);

        if (!$entity->canEdit($owner)) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Only owner can change the comments permissions',
            ]);
        }

        /** @var PermissionsManager */
        $manager = Di::_()->get('Permissions\Manager');
        $permissions = new Permissions();
        $permissions->setAllowComments($allowed);
        $manager->save($entity, $permissions);

        return Factory::response([
            'status' => 'success',
            'allowed' => $allowed,
        ]);
    }

    public function put($pages)
    {
        // TODO: Implement put() method.
    }

    public function delete($pages)
    {
        // TODO: Implement delete() method.
    }
}
