<?php
/**
 * Minds Group API
 * Group information endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\File as FileEntity;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;
use Minds\Plugin\Groups\Core\Membership;
use Minds\Plugin\Groups\Core\Notifications;
use Minds\Plugin\Groups\Core\Invitations;
use Minds\Plugin\Groups\Core\Group as CoreGroup;

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
        $membership = new Membership($group);
        $notifications = new Notifications($group);
        $invitations = new Invitations($group);

        $response['group'] = $group->export([], Session::getLoggedInUser());
        $response['group']['muted'] = $notifications->isMuted(Session::getLoggedInUser());
        $response['group']['invited'] = $invitations->isInvited(Session::getLoggedInUser());
        $response['group']['members'] = Factory::exportable($membership->getMembers());
        $response['group']['members:count'] = $membership->getMembersCount();
        $response['group']['requests'] = [];
        $response['group']['requests:count'] = $membership->getRequestsCount();
        $response['group']['can_edit'] = $membership->canEdit(Session::getLoggedInUser());

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        if (isset($pages[0])) {
            $creation = false;
            $group = EntitiesFactory::build($pages[0]);
        } else {
            $creation = true;
            $group = new GroupEntity();
        }

        if (isset($pages[1]) && $group->getGuid()) {
            // Specific updating (uploads)

            $response = [];
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
                    }
                    break;
                case "banner":
                    if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                        $this->uploadBanner($group, $_POST['banner_position']);
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

        if (!$group || !$group->getGuid()) {
            return Factory::response([]);
        }

        $current_user = Session::getLoggedInUser();
        $group_owner = EntitiesFactory::build($group->getOwnerObj());

        if (!$current_user->isAdmin() || $group_owner->getGuid() != $current_user->guid) {
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

        $resized = get_resized_image_from_uploaded_file('file', 2000);
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
