<?php
/**
 * Events listeners for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Events\Dispatcher;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Group;

class Events
{
    /**
     * Initialize events
     */
    public static function setup()
    {
        \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function ($hook, $type, $return, $row) {
            if ($row->type == 'group') {
                $entity = new GroupEntity();
                $entity->loadFromArray((array) $row);

                return $entity;
            }
        });

        Dispatcher::register('acl:read', 'activity', function ($e) {

            $access = EntitiesFactory::build($entity->access_id);

            // TODO: [emi] Find a better way to check
            if (!is_object($access) || !method_exists($access, 'getType') || $access->getType() != 'group') {
                return;
            }

            $params = $e->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];
            $membership = new Membership($access);

            $e->setResponse($membership->isMember($user->guid));
        });

        Dispatcher::register('activity:container', 'group', function ($e) {
            $params = $e->getParameters();
            $group = EntitiesFactory::build($params['group']);

            $notifications = new Notifications($params['container']);
            $notifications->queue($params['activity']);
        });

        Dispatcher::register('notification:dispatch', 'group', function ($e) {
            $params = $e->getParameters();
            $group = EntitiesFactory::build($params['entity']);
            $notifications = new Notifications($group);
            $e->setResponse($notifications->send($params['params']));
        });

        Dispatcher::register('cleanup:dispatch', 'group', function ($e) {
            $params = $e->getParameters();
            $e->setResponse(Membership::cleanup($params['group']));
        });
    }
}
