<?php
/**
 * Minds Group API
 * Membership-related operations
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Session;
use Minds\Core\Security\ACL;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User as User;
use Minds\Plugin\Groups\Core\Membership as CoreMembership;

class membership implements Interfaces\Api
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
        $membership = new CoreMembership($group);

        $options = [
            'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
            'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
        ];

        if (!isset($pages[1])) {
            $pages[1] = "members";
        }

        $response = [];

        switch ($pages[1]) {
          case "requests":
            $users = $membership->getRequests($options);

            if (!$users) {
                return Factory::response([]);
            }

            $response['users'] = Factory::exportable($users);
            $response['load-next'] = end($users)->user;
            break;
          case "bans":
            if (!ACL::_()->write($group, Session::getLoggedInUser())) {
                return Factory::response([]);
            }

            $users = $membership->getBannedUsers();

            if (!$users) {
                return Factory::response([]);
            }

            $response['users'] = Factory::exportable($users);
            $response['load-next'] = end($users)->user;
            break;
          case "members":
          default:
            $members = $membership->getMembers($options);

            if (!$members) {
                return Factory::response([]);
            }

            $response['members'] = Factory::exportable($members);
            $response['load-next'] = end($members)->getGuid();
            break;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        $group = EntitiesFactory::build($pages[0]);
        $membership = new CoreMembership($group);

        $options = [
            'limit' => isset($_GET['limit']) ? $_GET['limit'] : 12,
            'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
        ];

        if (!isset($pages[1])) {
            return Factory::response([]);
        }

        $response = [];

        switch ($pages[1]) {
            case 'kick':
            $user = $_POST['user'];

            if (!$user) {
                break;
            }

            $response['done'] = (bool) $membership->kick($user, Session::getLoggedInUser());
            break;

            case 'ban':
            $user = $_POST['user'];

            if (!$user) {
                break;
            }

            $response['done'] = (bool) $membership->ban($user, Session::getLoggedInUser());
            break;

            case 'unban':
            $user = $_POST['user'];

            if (!$user) {
                break;
            }

            $response['done'] = (bool) $membership->unban($user, Session::getLoggedInUser());
            break;
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        $membership = new CoreMembership($group);
        if ($membership->isBanned($user)) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You are banned from this group'
            ]);
        }

        if (isset($pages[1])) {
            //Admin approval
            $user = new User($pages[1]);

            if ($group->join($user)) {
                return Factory::response([]);
            }
        }

        if ($group->join($user)) {
            return Factory::response([]);
        }

        return Factory::response([
            'status' => 'error',
            'message' => 'Could not join group'
        ]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $membership = new CoreMembership($group);
        $user = Session::getLoggedInUser();

        // TODO: [emi] Check if this logic makes sense

        if (isset($pages[1])) {
            //Admin approval
            $membership->cancelRequest($pages[1]);
            return Factory::response([]);
        }

        if ($group->leave($user)) {
            return Factory::response([]);
        }

        return Factory::response([
            'status' => 'error',
            'message' => 'Could not leave group'
        ]);
    }
}
