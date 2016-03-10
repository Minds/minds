<?php
/**
 * Minds Group API
 * Group invitations endpoints
 */
namespace Minds\Plugin\Groups\Controllers\api\v1;

use Minds\Core;
use Minds\Core\Session;
use Minds\Core\Security\ACL;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User;

use Minds\Plugin\Groups\Core\Membership as CoreMembership;
use Minds\Plugin\Groups\Core\Invitations as CoreInvitations;
use Minds\Plugin\Groups\Entities\Group as GroupEntity;

class invitations implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!ACL::_()->read($this->group, $user)) {
            return Factory::response([]);
        }

        $invitees = (new CoreInvitations($group))->getInvitations([
            'hydrate' => true,
            'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
            'offset' => isset($_GET['offset']) ? $_GET['offset'] : ''
        ]);

        $response = [
            'users' => $invitees
        ];

        if ($invitees) {
            $response['load-next'] = (string) end($invitees)->guid;
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        if ($pages[0] == 'preinvite') {

            if (!isset($_POST['user']) || !$_POST['user']) {
                return Factory::response([
                    'done' => false
                ]);
            }

            $user = Session::getLoggedInUser();
            $invitee = new User($_POST['user']);

            if (!$invitee || !$invitee->getGuid()) {
                return Factory::response([
                    'done' => false
                ]);
            }

            if ($user->getGuid() == $invitee->getGuid()) {
                return Factory::response([
                    'done' => false
                ]);
            }

            $invitations = new CoreInvitations(new GroupEntity());
            return Factory::response([
                'done' => $invitations->userHasSubscriber($user, $invitee)
            ]);
        }

        $group = EntitiesFactory::build($pages[0]);
        $invitee = Session::getLoggedInUser();

        if ($group->isMember($invitee)) {
            return Factory::response([]);
        }

        $invitations = new CoreInvitations($group);

        if (!$invitations->isInvited($invitee)) {
            return Factory::response([]);
        }

        $membership = new CoreMembership($group);
        if ($membership->isBanned($invitee)) {
            return Factory::response([]);
        }

        switch ($pages[1]) {
            case 'accept':
                return Factory::response([
                    'done' => $invitations->accept($invitee)
                ]);
            case 'decline':
                return Factory::response([
                    'done' => $invitations->decline($invitee)
                ]);
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$group || !$group->getGuid())
        {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($payload['invitee']) || !$payload['invitee'] || !ctype_alnum($payload['invitee'])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid username'
            ]);
        }

        $invitee = new User(strtolower($payload['invitee']));

        if (!$invitee || !$invitee->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        if ($user->getGuid() == $invitee->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'Cannot invite yourself'
            ]);
        }

        if ($group->isMember($invitee)) {
            return Factory::response([
                'done' => false,
                'error' => 'User is already a member'
            ]);
        }

        $invitations = new CoreInvitations($group);
        $invited = $invitations->invite($invitee, $user);

        if ($invited) {
            $membership = new CoreMembership($group);
            if ($membership->isBanned($invitee)) {
                $membership->unban($invitee, Session::getLoggedInUser());
            }
        }

        return Factory::response([
            'done' => $invited,
            'error' => !$invited ? 'User cannot be invited' : ''
        ]);
    }

    public function delete($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$group || !$group->getGuid())
        {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($payload['invitee']) || !$payload['invitee'] || !ctype_alnum($payload['invitee'])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid username'
            ]);
        }

        $invitee = new User(strtolower($payload['invitee']));

        if (!$invitee || !$invitee->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        if ($user->getGuid() == $invitee->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'Cannot uninvite yourself'
            ]);
        }

        if ($group->isMember($invitee)) {
            return Factory::response([
                'done' => false,
                'error' => 'User is already a member'
            ]);
        }

        $invitations = new CoreInvitations($group);
        $uninvited = $invitations->uninvite($invitee, $user);

        return Factory::response([
            'done' => $uninvited,
            'error' => !$uninvited ? 'User cannot be uninvited' : ''
        ]);
    }
}
