<?php
/**
 * Update Markers
 *
 * @author emi
 */

namespace Minds\Controllers\api\v2\notifications;

use Minds\Api\Factory;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\UpdateMarkers\Manager;
use Minds\Core\Notification\UpdateMarkers\UpdateMarker;
use Minds\Core\Session;
use Minds\Interfaces;

class markers implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $user = Session::getLoggedinUser();

        if (!$user) {
            return;
        }

        $entity_type = $_GET['type'] ?? 'group';

        $opts = [
            'user_guid' => $user->guid,
            'entity_type' => $entity_type,
        ];        

        $manager = (new Manager());
        $list = $manager->getList($opts);


        return Factory::response([
            'markers' => Factory::exportable($list), 
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        switch ($pages[0]) {
            case "read":
                $marker = new UpdateMarker;
                $marker
                    ->setUserGuid(Session::getLoggedInUserGuid())
                    ->setEntityGuid($_POST['entity_guid'])
                    ->setEntityType($_POST['entity_type'])
                    ->setMarker($_POST['marker'])
                    ->setReadTimestamp(time());

                $manager = (new Manager());
                $manager->add($marker);
                break;
        }
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
    }
}
