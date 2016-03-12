<?php
/**
 * Minds Group API
 * Group information endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Session;
use Minds\Core\Security\ACL;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\User;
use Minds\Entities\File as FileEntity;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Invitations;
use Minds\Plugin\Groups\Core\Group as CoreGroup;
use Minds\Plugin\Groups\Core\Activity;

class group implements Interfaces\Api
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/group/group/:guid
     */
    public function get($pages)
    {
        $group = EntitiesFactory::build($pages[0]);
        $activity = new Activity($group);
        $membership = new Membership($group);
        $notifications = new Notifications($group);
        $invitations = new Invitations($group);

        $response['group'] = $group->export();
        $response['group']['activity:count'] = $activity->count();

        $user = Session::getLoggedInUser();
        $can_read = $user && ACL::_()->read($group, $user);

        $response['group']['is:invited'] = $user ? $invitations->isInvited($user) : false;
        $response['group']['is:awaiting'] = $user ? $membership->isAwaiting($user) : false;
        $response['group']['is:banned'] = $user ? $membership->isBanned($user) : false;

        $response['group']['members'] = $can_read ? Factory::exportable($membership->getMembers()) : [];
        $response['group']['members:count'] = $can_read ? $membership->getMembersCount() : '';
        $response['group']['is:member'] = $can_read ? $group->isMember($user) : false;

        if ($can_read) {
            $response['group']['is:muted'] = $notifications->isMuted($user);
            $response['group']['is:creator'] = $group->isCreator($user);

            $owner = $group->isOwner($user);
            $response['group']['is:owner'] = $owner;

            if ($owner) {
                $response['group']['requests:count'] = $membership->getRequestsCount();
            }
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $user = Session::getLoggedInUser();

        if (isset($pages[0])) {
            $creation = false;
            $group = EntitiesFactory::build($pages[0]);

            if (!ACL::_()->write($group, $user)) {
                throw new \Exception('You do not have enough permissions');
            }
        } else {
            $creation = true;
            $group = new GroupEntity();
        }

        if (isset($pages[1]) && $group->getGuid()) {
            // Specific updating (uploads)

            $response = [ 'done' => false ];
            $group_owner = EntitiesFactory::build($group->getOwnerObj());

            switch ($pages[1]) {
                case "avatar":
                    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                        $icon_sizes = Core\Config::_()->get('icon_sizes');
                        foreach (['tiny', 'small', 'medium', 'large'] as $size) {
                            $resized = get_resized_image_from_uploaded_file('file', $icon_sizes[$size]['w'], $icon_sizes[$size]['h'], $icon_sizes[$size]['square']);

                            $file = new FileEntity();
                            $file->owner_guid = $group_owner->getGuid();
                            $file->setFilename("groups/{$group->getGuid()}{$size}.jpg");
                            $file->open('write');
                            $file->write($resized);
                            $file->close();
                        }
                        $group->setIconTime(time());
                        $group->save();
                        $response['done'] = true;
                    }
                    break;
                case "banner":
                    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                        $this->uploadBanner($group, $_POST['banner_position']);
                        $response['done'] = true;
                    }
                    break;
            }

            return Factory::response($response);
        }

        // Creation / Updating

        if (isset($_POST['name'])) {
            $group->setName($_POST['name']);
        }

        if (isset($_POST['briefdescription'])) {
            $sanitized_briefdescription = trim(strip_tags($_POST['briefdescription']));

            if (strlen($sanitized_briefdescription) > 255) {
                $sanitized_briefdescription = substr($sanitized_briefdescription, 0, 255);
            }

            $group->setBriefDescription($sanitized_briefdescription);
        }

        if (isset($_POST['membership'])) {
            $group->setMembership($_POST['membership']);

            if ($_POST['membership'] == 2) {
                $group->setAccessId(ACCESS_PUBLIC);

                if (!$creation) {
                    (new Membership($group))->acceptAllRequests();
                }
            } elseif ($_POST['membership'] == 0) {
                $group->setAccessId(ACCESS_PRIVATE);
            }
        }

        if (isset($_POST['tags'])) {
            // Sanitize tags
            $tags = explode(',', $_POST['tags']);
            $sanitized_tags = [];

            if (count($tags) > 5) {
                $tags = array_slice($tags, 0, 5);
            }

            foreach ($tags as $tag) {
                $tag = trim(strip_tags($tag));

                if (strlen($tag) > 25) {
                    $tag = substr($tag, 0, 25);
                }

                $sanitized_tags[] = $tag;
            }

            $group->setTags(implode(', ', $sanitized_tags));
        }

        if ($creation) {
            $group
            ->setAccessId(2)
            ->setOwnerObj(Session::getLoggedInUser());
        }

        $group->save();

        if ($creation) {
            // Join group
            $group->join(Session::getLoggedInUser());
        }

        if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->uploadBanner($group, $_POST['banner_position']);
        }

        $response = array();
        $response['guid'] = $group->getGuid();


        if ($creation && isset($_POST['invitees']) && $_POST['invitees']) {
            $invitations = new Invitations($group);
            $invitees = explode(',', $_POST['invitees']);

            foreach ($invitees as $invitee) {
                $invitee = new User(strtolower(trim($invitee)));

                if (!$invitee || !$invitee->getGuid()) {
                    continue;
                }

                if ($user->getGuid() == $invitee->getGuid()) {
                    continue;
                }

                $invited = $invitations->invite($invitee, $user);
            }
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$user || !$group || !$group->getGuid()) {
            return Factory::response([]);
        }

        $group_owner = EntitiesFactory::build($group->getOwnerObj());

        if (!ACL::_()->write($group, $user)) {
            return Factory::response([]);
        }

        $core_group = new CoreGroup($group);
        $deleted = $core_group->delete();

        if (!$deleted) {
            return Factory::response([
                'error' => 'Cannot delete group',
                'done' => false
            ]);
        }

        return Factory::response([
            'done' => true
        ]);

    }

    protected function uploadBanner(GroupEntity $group, $banner_position)
    {
        $group_owner = EntitiesFactory::build($group->getOwnerObj());

        $resized = get_resized_image_from_uploaded_file('file', 3840, 1404);
        $file = new FileEntity();
        $file->owner_guid = $group_owner->getGuid();
        $file->setFilename("group/{$group->getGuid()}.jpg");
        $file->open('write');
        $file->write($resized);
        $file->close();

        $group
        ->setBanner(true)
        ->setBannerPosition($banner_position);

        $group->save();

        return $group;
    }
}
