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
use Minds\Plugin\Groups\Core\Management as Management;

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

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
        $membership = (new CoreMembership($group))->setActor(Session::getLoggedInUser());

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
                if (!$membership->canActorWrite($group)) {
                    return Factory::response([]);
                }

                $users = $membership->getRequests($options);

                if (!$users) {
                    return Factory::response([]);
                }

                $response['users'] = Factory::exportable($users);
                $response['load-next'] = end($users)->getGuid();
                break;
            case "bans":
                if (!$membership->canActorWrite($group)) {
                    return Factory::response([]);
                }

                $users = $membership->getBannedUsers();

                if (!$users) {
                    return Factory::response([]);
                }

                $response['users'] = Factory::exportable($users);
                $response['load-next'] = end($users)->getGuid();
                break;
            case "members":
            default:
                if (!$membership->canActorRead($group)) {
                    return Factory::response([]);
                }

                $members = $membership->getMembers($options);
                $management = new Management($group);

                if (!$members) {
                    return Factory::response([]);
                }

                $response['members'] = Factory::exportable($members);

                for ($i = 0; $i < count($response['members']); $i++) {
                    $response['members'][$i]['is:member'] = true;
                    $response['members'][$i]['is:awaiting'] = false;
                    $response['members'][$i]['is:creator'] = $management->isCreator($response['members'][$i]['guid']);
                    $response['members'][$i]['is:owner'] = $management->isOwner($response['members'][$i]['guid']);
                }

                $response['load-next'] = end($members)->getGuid();
                break;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();
        $membership = (new CoreMembership($group))->setActor($actor);

        if (!isset($pages[1])) {
            return Factory::response([]);
        }

        $response = [];

        try {
            switch ($pages[1]) {
                case 'cancel':
                $response['done'] = $membership->cancelRequest($actor);
                break;

                case 'kick':
                $user = $_POST['user'];

                if (!$user) {
                    break;
                }

                $response['done'] = $membership->kick($user);
                break;

                case 'ban':
                $user = $_POST['user'];

                if (!$user) {
                    break;
                }

                $response['done'] = $membership->ban($user);
                break;

                case 'unban':
                $user = $_POST['user'];

                if (!$user) {
                    break;
                }

                $response['done'] = $membership->unban($user);
                break;
            }

            return Factory::response($response);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        $membership = new CoreMembership($group);

        if (isset($pages[1])) {
            //Admin approval
            try {
                $joined = $membership->setActor($user)->join($pages[1]);

                return Factory::response([
                    'done' => $joined
                ]);
            } catch (GroupOperationException $e) {
                return Factory::response([
                    'done' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Normal join
        try {
            $joined = $group->join($user);

            return Factory::response([
                'done' => $joined
            ]);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $actor = Session::getLoggedInUser();
        $membership = new CoreMembership($group);

        // TODO: [emi] Check if this logic makes sense

        if (isset($pages[1])) {
            //Admin approval
            try {
                $cancelled = $membership->setActor($actor)->cancelRequest($pages[1]);

                return Factory::response([
                    'done' => $cancelled
                ]);
            } catch (GroupOperationException $e) {
                return Factory::response([
                    'done' => false,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Normal leave
        try {
            $left = $group->leave($actor);

            return Factory::response([
                'done' => $left
            ]);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
