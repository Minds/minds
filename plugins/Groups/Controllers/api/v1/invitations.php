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

use Minds\Plugin\Groups;
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

        $invitees = (new Groups\Core\Invitations)
          ->setGroup($group)
          ->getInvitations([
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
        if ($pages[0] == 'check') {
            return $this->checkOnly();
        }
        // End check-only response

        $group = EntitiesFactory::build($pages[0]);
        $invitee = Session::getLoggedInUser();

        if ($group->isMember($invitee)) {
            return Factory::response([]);
        }

        $invitations = (new Groups\Core\Invitations)
          ->setGroup($group)
          ->setActor($invitee);

        if (!$invitations->isInvited($invitee)) {
            return Factory::response([]);
        }

        $membership = new Groups\Core\Membership($group);
        if ($membership->isBanned($invitee)) {
            return Factory::response([]);
        }

        $done = false;

        try {
            switch ($pages[1]) {
                case 'accept':
                    $done = $invitations->accept();
                    break;
                case 'decline':
                    $done = $invitations->decline();
                    break;
            }
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'done' => $done
        ]);
    }

    public function put($pages)
    {
        Factory::isLoggedIn();

        $group = EntitiesFactory::build($pages[0]);
        $user = Session::getLoggedInUser();
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$group || !$group->getGuid()) {
            return Factory::response([
                'done' => false,
                'error' => 'No group'
            ]);
        }

        if (!isset($payload['guid']) || !$payload['guid'] || !is_numeric($payload['guid'])) {
            return Factory::response([
                'done' => false,
                'error' => 'Invalid guid'
            ]);
        }

        $invitee = new User($payload['guid']);

        if (!$invitee || !$invitee->username) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $membership = (new Groups\Core\Membership())
          ->setGroup($group);
        $banned = $membership->isBanned($invitee);

        if ($banned && !$group->isOwner($user)) {
            return Factory::response([
                'done' => false,
                'error' => 'User is banned from this group'
            ]);
        }

        $invitations = (new Groups\Core\Invitations)
          ->setGroup($group)
          ->setActor($user);

        try {
            $invited = $invitations->invite($invitee);
        } catch (GroupOperationException $e) {
            return Factory::response([
                'done' => false,
                'error' => $e->getMessage()
            ]);
        }

        if ($banned) {
            $membership->setActor($user)->unban($invitee);
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

        if (!$group || !$group->getGuid()) {
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

        $invitations = (new Groups\Core\Invitations)
          ->setGroup($group)
          ->setActor($user);

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

    protected function checkOnly()
    {
        if (!isset($_POST['user']) || !$_POST['user']) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        $user = Session::getLoggedInUser();
        $invitee = new User($_POST['user']);

        if (!$invitee || !$invitee->guid) {
            return Factory::response([
                'done' => false,
                'error' => 'User not found'
            ]);
        }

        if ($user->guid == $invitee->guid) {
            return Factory::response([
                'done' => false,
                'error' => 'You cannot invite yourself'
            ]);
        }

        $invitations = (new Groups\Core\Invitations)
          ->setGroup(new GroupEntity());

        return Factory::response([
            'done' => $invitations->userHasSubscriber($user, $invitee)
        ]);
    }
}
