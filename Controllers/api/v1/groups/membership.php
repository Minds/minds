<?php
/**
* Minds Group API
* Membership-related operations
*/
namespace Minds\Controllers\api\v1\groups;

use Minds\Core;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Core\Search\Documents;

use Minds\Exceptions\GroupOperationException;

class membership implements Interfaces\Api
{
    /**
    * Returns the members
    * @param array $pages
    *
    * API:: /v1/group/group/:guid
    */
    public function get($pages)
    {
        $group = EntitiesFactory::build($pages[0]);
        $membership = (new Core\Groups\Membership)
          ->setGroup($group)
          ->setActor(Session::getLoggedInUser());

        $loggedInUser = Core\Session::getLoggedinUser();

        if (!$group->isPublic() && !$membership->isMember($loggedInUser) && !$loggedInUser->isAdmin()) {
            return Factory::response([]);
        }

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
            case "search":
                if (!isset($_GET['q']) || !$_GET['q']) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => 'Missing query'
                    ]);
                }

                $query = Documents::escapeQuery((string) $_GET['q']);
                $query = "({$query})^5";

                $opts = [
                    'limit' => $_GET['limit'] ?: 12,
                    'type' => 'user',
                    'flags' => [
                        "+group_membership:\"{$group->getGuid()}\""
                    ]
                ];

                if (isset($_GET['offset']) && $_GET['offset']) {
                    $opts['offset'] = $_GET['offset'];
                }

                $guids = (new Documents())->query($_GET['q'], $opts);
                $response = [];

                if (!$guids) {
                    return Factory::response([
                        'members' => [],
                    ]);
                }

                if ($guids) {
                    $members = Core\Entities::get(['guids' => $guids]);

                    $response['members'] = Factory::exportable($members);

                    for ($i = 0; $i < count($response['members']); $i++) {
                        $response['members'][$i]['is:moderator'] = $group->isModerator($response['members'][$i]['guid']);
                        $response['members'][$i]['is:owner'] = $group->isOwner($response['members'][$i]['guid']);
                        $response['members'][$i]['is:member'] = true;
                        $response['members'][$i]['is:awaiting'] = false;
                    }
                }
            break;
            case "owners":
                if (!$membership->canActorRead($group)) {
                    return Factory::response([]);
                }

                $owners = $membership->getOwners($options);

                if (!$owners) {
                    return Factory::response([]);
                }

                $response['owners'] = Factory::exportable($owners);
                $response['load-next'] = end($owners)->getGuid();
                break;
            case "members":
            default:
                if (!$membership->canActorRead($group)) {
                    return Factory::response([]);
                }

                $members = $membership->getMembers($options);

                if (!$members) {
                    return Factory::response([]);
                }

                $response['members'] = Factory::exportable($members);

                for ($i = 0; $i < count($response['members']); $i++) {
                    $response['members'][$i]['is:moderator'] = $group->isModerator($response['members'][$i]['guid']);
                    $response['members'][$i]['is:owner'] = $group->isOwner($response['members'][$i]['guid']);
                    $response['members'][$i]['is:member'] = true;
                    $response['members'][$i]['is:awaiting'] = false;
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
        $membership = (new Core\Groups\Membership)
          ->setGroup($group)
          ->setActor($actor);

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

        $membership = (new Core\Groups\Membership)->setGroup($group);
        $invitations = (new Core\Groups\Invitations)->setGroup($group)->setActor($user);

        if (Core\Security\ACL\Block::_()->isBlocked($user, $group->owner_guid)) {
            return Factory::response([
                'status' => 'error',
                'stage' => 'initial',
                'message' => "You are not allowed to join this group"
            ]);
        }

        if (isset($pages[1])) {
            //Admin approval
            try {
                $joined = $membership->setActor($user)->join($pages[1]);

                $event = new Core\Analytics\Metrics\Event();
                $event->setType('action')
                    ->setProduct('platform')
                    ->setAction("join")
                    ->setUserGuid((string) $user->guid)
                    ->setUserPhoneNumberHash($user->getPhoneNumberHash())
                    ->setEntityGuid((string) $group->guid)
                    ->setEntityType($group->type)
                    ->push();

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
            if ($invitations->isInvited($user)) {
                $joined = $invitations->accept();
            } else {
                $joined = $group->join($user);
            }

            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setProduct('platform')
                ->setAction("join")
                ->setEntityMembership(2)
                ->setUserGuid((string) $user->guid)
                ->setUserPhoneNumberHash($user->getPhoneNumberHash())
                ->setEntityGuid((string) $group->guid)
                ->setEntityType($group->type)
                ->push();

            return Factory::response([
                'done' => $joined
            ]);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'status' => 'error',
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
        $membership = (new Core\Groups\Membership)
          ->setGroup($group);

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
