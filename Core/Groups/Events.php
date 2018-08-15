<?php
/**
 * Events listeners for Groups
 */
namespace Minds\Core\Groups;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Entities\Group as GroupEntity;

class Events
{
    /**
     * Initialize events
     */
    public function register()
    {
        \elgg_register_plugin_hook_handler('entities_class_loader', 'all', function ($hook, $type, $return, $row) {
            if ($row->type == 'group') {
                $entity = new GroupEntity();
                $entity->loadFromArray((array) $row);

                return $entity;
            }
        });

        Dispatcher::register('acl:read', 'activity', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $access_id = $entity->access_id;
            $user = $params['user'];

            $group = $entity->getContainerEntity();

            if ($group instanceof GroupEntity) {
                $membership = Membership::_($group);

                $e->setResponse($group->isPublic() || $membership->isMember($user->guid));
            }
        });

        Dispatcher::register('acl:write', 'activity', function ($e) {
            $params = $e->getParameters();
            $entity = $params['entity'];
            $user = $params['user'];

            $group = $entity->getContainerEntity();

            if (!($group instanceof GroupEntity)) {
                return;
            }

            $e->setResponse(($group->isOwner($user->guid) || $group->isModerator($user->guid)) && $group->isMember($user->guid));
        });

        Dispatcher::register('delete', 'activity', function ($e) {
            $params = $e->getParameters();
            $activity = $params['entity'];

            if (!$activity) {
                return;
            }

            $group = $activity->getContainerEntity();

            if (!($group instanceof GroupEntity)) {
                return;
            }

            /** @var Groups\AdminQueue $adminQueue */
            $adminQueue = Di::_()->get('Groups\AdminQueue');
            $adminQueue->delete($group, $activity);
        });

        Dispatcher::register('acl:read', 'group', function ($e) {
            $params = $e->getParameters();
            $group = $params['entity'];
            $user = $params['user'];

            $e->setResponse($group->isPublic() || $group->isMember($user->guid));
        });

        Dispatcher::register('acl:write', 'group', function ($e) {
            $params = $e->getParameters();
            $group = $params['entity'];
            $user = $params['user'];

            $isOwner = $group->isOwner($user->guid);
            $isModerator = $group->isModerator($user->guid);
            $isMember = $group->isMember($user->guid);

            if ($isOwner && $isMember) {
                $e->setResponse(true);
                return;
            } elseif ($isModerator && $isMember) {
                $e->setResponse(true);
                return;
            }

            $e->setResponse(false);
        });

        Dispatcher::register('acl:write:container', 'group', function ($e) {
            $params = $e->getParameters();
            $group = $params['container'];
            $user = $params['user'];

            $e->setResponse($group->isOwner($user->guid) && $group->isMember($user->guid));
        });

        Dispatcher::register('activity:container:prepare', 'group', function ($e) {
            $params = $e->getParameters();

            $group = $params['container'];
            $activity = $params['activity'];

            if ($group->getModerated() && !$group->isOwner($activity->owner_guid)) {
                $key = "activity:container:{$group->guid}";
                $index = array_search($key, $activity->indexes);
                if ($index !== false) {
                    unset($activity->indexes[$index]);
                }

                $activity->setPending(true);
            }
        });

        Dispatcher::register('activity:container', 'group', function ($e) {
            $params = $e->getParameters();

            $group = $params['container'];
            $activity = $params['activity'];

            if ($group->getModerated() && $activity->getPending()) {
                Di::_()->get('Groups\Feeds')
                    ->setGroup($group)
                    ->queue($activity);
            } else {
                (new Notifications())
                    ->setGroup($group)
                    ->queue($activity);
            }
        });

        Dispatcher::register('cleanup:dispatch', 'group', function ($e) {
            $params = $e->getParameters();
            $e->setResponse(Membership::cleanup($params['group']));
        });
    }
}
