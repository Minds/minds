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

use Minds\Plugin\Groups\Exceptions\GroupOperationException;

class invitations implements Interfaces\Api
{
    public function get($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();

        if (!$group->isMember($user)) {
            return Factory::response([
                'error' => 'You cannot read invitations'
            ]);
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

        // Start check-only response
        // TODO: [emi] Move to a helper method in this class
        if ($pages[0] == 'check') {

            if (!isset($_POST['user']) || !$_POST['user']) {
                return Factory::response([
                    'done' => false
                ]);
            }

            $user = Session::getLoggedInUser();
            $invitee = new User($_POST['user']);

            if (!$invitee || !$invitee->guid) {
                return Factory::response([
                    'done' => false
                ]);
            }

            if ($user->guid == $invitee->guid) {
                return Factory::response([
                    'done' => false
                ]);
            }

            $invitations = new CoreInvitations(new GroupEntity());
            return Factory::response([
                'done' => $invitations->userHasSubscriber($user, $invitee)
            ]);
        }
        // End check-only response

        $group = EntitiesFactory::build($pages[0]);
        $invitee = Session::getLoggedInUser();

        if ($group->isMember($invitee)) {
            return Factory::response([]);
        }

        $invitations = (new CoreInvitations($group))->setActor($invitee);

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
                    'done' => $invitations->accept()
                ]);
            case 'decline':
                return Factory::response([
                    'done' => $invitations->decline()
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

        if (!$invitee || !$invitee->guid) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $membership = new CoreMembership($group);
        $banned = $membership->isBanned($invitee);

        if ($banned && !$group->isOwner($user)) {
            return Factory::response([
                'done' => false,
                'error' => 'User is banned from this group'
            ]);
        }

        $invitations = (new CoreInvitations($group))->setActor($user);

        try {
            $invited = $invitations->invite($invitee);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        if ($banned) {
            $membership->unban($invitee, $user);
        }

        return Factory::response([
            'done' => $invited
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

        if (!$invitee || !$invitee->guid) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $invitations = (new CoreInvitations($group))->setActor($user);

        try {
            $uninvited = $invitations->uninvite($invitee);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'done' => $uninvited
        ]);
    }
}
