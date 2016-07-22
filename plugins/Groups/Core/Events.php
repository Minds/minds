<?php
/**
 * Events listeners for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Events\Dispatcher;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups;

class Events
{
    /**
     * Initialize events
     */
    public static function setup()
    {
        \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function ($hook, $type, $return, $row) {
            if ($row->type == 'group') {
                $entity = new Groups\Entities\Group();
                $entity->loadFromArray((array) $row);

                return $entity;
            }
        });

        Dispatcher::register('acl:read', 'activity', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $access_id = $entity->access_id;
            $user = $params['user'];

            //fake group entity
            $group = new Groups\Entities\Group(true, true);
            $group->setGuid($access_id); //creates a group without loading the db

            $membership = Membership::_($group);

            $e->setResponse($membership->isMember($user->guid));
        });

        Dispatcher::register('acl:write', 'activity', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];

            $group = $entity->getContainerEntity();

            if (!($group instanceof Groups\Entities\Group)) {
                return;
            }

            $e->setResponse($group->isOwner($user->guid) && $group->isMember($user->guid));
        });

        Dispatcher::register('acl:read', 'group', function ($e) {
            $params = $e->getParameters();
            $group = $params['entity'];
            $user = $params['user'];

            $e->setResponse($group->isMember($user->guid));
        });

        Dispatcher::register('acl:write', 'group', function ($e) {
            $params = $e->getParameters();
            $group = $params['entity'];
            $user = $params['user'];

            $e->setResponse($group->isOwner($user->guid) && $group->isMember($user->guid));
        });

        Dispatcher::register('activity:container', 'group', function ($e) {
            $params = $e->getParameters();

            $notifications = (new Notifications)->setGroup($params['container']);
            $notifications->queue($params['activity']);
        });

        Dispatcher::register('notification:dispatch', 'group', function ($e) {
            $params = $e->getParameters();

            $group = new Groups\Entities\Group();
            $group->loadFromGuid($params['entity']);

            $notifications = (new Notifications)->setGroup($group);
            $e->setResponse($notifications->send($params['params']));
            echo "[]: sent to $group->guid \n";
        });

        Dispatcher::register('cleanup:dispatch', 'group', function ($e) {
            $params = $e->getParameters();
            $e->setResponse(Membership::cleanup($params['group']));
        });
    }
}
