<?php
/**
 * Minds Group API
 * Group information endpoints
 */
namespace Minds\Controllers\api\v1\groups;

use Minds\Core;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\User;
use Minds\Entities\File as FileEntity;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\Group as GroupEntity;

use Minds\Exceptions\GroupOperationException;

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
        $user = Session::getLoggedInUser();

        $response = [];

        if ($group) {
            $response['group'] = $group->export();

            $membership = (new Core\Groups\Membership)
              ->setGroup($group);

            $notifications = (new Core\Groups\Notifications)
              ->setGroup($group);

            $response['group']['is:muted'] = $user && $notifications->isMuted($user);

            $canRead = $user && ($membership->isMember($user) || $user->isAdmin());
            $canModerate = $user && ($group->isOwner($user) || $group->isModerator($user));

            if (!$canRead) {
                // Restrict output if cannot read
                $allowed = ['guid', 'name', 'membership', 'type', 'is:awaiting', 'is:invited' ];
                if ($response['group']['membership'] == 2) {
                    $allowed = array_merge($allowed, ['members:count', 'activity:count', 'comments:count']);
                }

                $response['group'] = array_filter($response['group'], function ($key) use ($allowed) {
                    return in_array($key, $allowed);
                }, ARRAY_FILTER_USE_KEY);
            }

            if ($canModerate) {
                /** @var Core\Groups\Feeds $feeds */
                $feeds = Core\Di\Di::_()->get('Groups\Feeds');
                $count = (int) $feeds->setGroup($group)->count();

                $response['group']['adminqueue:count'] = $count;
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

            if (!$group->isOwner($user) && !Core\Session::isAdmin()) {
                return Factory::response([
                    'error' => 'You cannot edit this group'
                ]);
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
                        $this->uploadAvatar($group);
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
            $sanitized_briefdescription = htmlspecialchars(trim($_POST['briefdescription']));

            if (strlen($sanitized_briefdescription) > 2048) {
                $sanitized_briefdescription = substr($sanitized_briefdescription, 0, 2048);
            }

            $group->setBriefDescription($sanitized_briefdescription);
        }

        if (isset($_POST['membership'])) {
            $group->setMembership($_POST['membership']);

            if ($_POST['membership'] == 2) {
                $group->setAccessId(ACCESS_PUBLIC);

                if (!$creation) {
                    (new Core\Groups\Membership)
                      ->setGroup($group)
                      ->setActor($user)
                      ->acceptAllRequests();
                }
            } elseif ($_POST['membership'] == 0) {
                $group->setAccessId(ACCESS_PRIVATE);
            }
        }

        if (isset($_POST['moderated'])) {
            $oldModerationValue = $group->getModerated();
            $group->setModerated($_POST['moderated']);

            $moderationChange = $oldModerationValue != $group->getModerated();
        }


        if (isset($_POST['default_view'])) {
            $group->setDefaultView($_POST['default_view']);
        }

        if (isset($_POST['tags'])) {
            $tags = $_POST['tags'];
            $sanitized_tags = [];

            foreach ($tags as $tag) {
                $tag = trim(strip_tags($tag));

                if (strlen($tag) > 25) {
                    $tag = substr($tag, 0, 25);
                }

                $sanitized_tags[] = $tag;
            }

            $group->setTags($sanitized_tags);
        }

        if ($creation) {
            $group->setAccessId(2)
              ->setOwnerObj($user);
        }

        $group->save();

        if ($creation) {
            // Join group
            try {
                $group->join($user, [ 'force' => true ]);
            } catch (GroupOperationException $e) {
            }
        }

        if (!$creation && $moderationChange && !$group->getModerated()) {
            Core\Di\Di::_()->get('Groups\Feeds')
                ->setGroup($group)
                ->approveAll();
        }

        // Legacy behavior
        if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $this->uploadBanner($group, $_POST['banner_position']);
        }

        $response = array();
        $response['guid'] = $group->getGuid();

        if ($creation && isset($_POST['invitees']) && $_POST['invitees']) {
            $invitations = (new Core\Groups\Invitations)->setGroup($group)->setActor($user);
            $invitees = $_POST['invitees'];

            foreach ($invitees as $invitee) {
                if (is_numeric($invitee)) {
                    try {
                        $invitee = new User($invitee);
                        $invitations->invite($invitee);
                    } catch (GroupOperationException $e) {
                    }
                }
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

        if (!$group || !$group->getGuid()) {
            return Factory::response([]);
        }

        $canDelete = Session::isAdmin();

        if (!$canDelete && $group->isCreator($user)) {
            $canDelete = $group->getMembersCount() <= 1;
        }

        if (!$canDelete) {
            return Factory::response([
                'error' => 'You cannot delete this group'
            ]);
        }

        try {
            $group->delete();

            return Factory::response([
                'done' => true
            ]);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Uploads a Group avatar
     * @param  GroupEntity $group
     * @return GroupEntity
     */
    protected function uploadAvatar(GroupEntity $group)
    {
        $icon_sizes = Core\Config::_()->get('icon_sizes');
        $group_owner = EntitiesFactory::build($group->getOwnerObj());

        foreach (['tiny', 'small', 'medium', 'large'] as $size) {
            $resized = get_resized_image_from_uploaded_file('file', $icon_sizes[$size]['w'], $icon_sizes[$size]['h'], $icon_sizes[$size]['square']);

            $file = new FileEntity();
            $file->owner_guid = $group->owner_guid ?: $group_owner->getGuid();
            $file->setFilename("groups/{$group->getGuid()}{$size}.jpg");
            $file->open('write');
            $file->write($resized);
            $file->close();
        }

        $group->setIconTime(time());
        $group->save();

        return $group;
    }

    /**
     * Uploads a Group banner
     * @param  GroupEntity $group
     * @return GroupEntity
     */
    protected function uploadBanner($group, $banner_position)
    {
        $group_owner = EntitiesFactory::build($group->getOwnerObj());

        $resized = get_resized_image_from_uploaded_file('file', 3840, 1404);
        $file = new FileEntity();
        $file->owner_guid = $group->owner_guid ?: $group_owner->getGuid();
        $file->setFilename("group/{$group->getGuid()}.jpg");
        $file->open('write');
        $file->write($resized);
        $file->close();

        $group
          ->setBanner(time())
          ->setBannerPosition($banner_position);

        $group->save();

        return $group;
    }
}
