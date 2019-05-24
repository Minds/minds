<?php

namespace Minds\Controllers\api\v2\sendwyre;

use Minds\Core\Session;
use Minds\Core\SendWyre\SendWyreAccount;
use Minds\Core\SendWyre\Manager;
use Minds\Interfaces\Api;
use Minds\Core\Di\Di;
use Minds\Api\Factory;

class accounts implements Api
{
    //GET /api/v2/sendwyre/accounts
    public function get($pages)
    {
        /** @var \Minds\Core\SendWyre\Manager $manager */
        $manager = Di::_()->get('SendWyre\Manager');

        $user = Session::getLoggedInUser();

        try {
            $account = $manager->get($user->guid);
            if (!$account) {
                return Factory::response([]);
            }

            return Factory::response([
                'status' => 'success',
                'account' => $account->export(),
            ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function options($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    //PUT /api/v2/sendwyre/accounts/:sendwyre_account_id
    public function put($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'sendwyre_account_id must be provided']);
        }
        $user = Session::getLoggedInUser();
        $accountId = $pages[0];

        /** @var \Minds\Core\SendWyre\Manager $manager */
        $manager = Di::_()->get('SendWyre\Manager');
        try {
            $account = (new SendWyreAccount())
                ->setUserGuid($user->guid)
                ->setSendWyreAccountId($accountId);

            $manager->save($account);

            return Factory::response([
                    'status' => 'success',
                    'account' => $account->export(),
                ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    //DELETE /api/v2/sendwyre/accounts/:user_guid
    public function delete($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => 'user_guid must be provided']);
        }

        $user = Session::getLoggedInUser();
        $userGuid = $pages[0];

        if (!Session::isAdmin() && $user->guid != $userGuid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Insufficient permissions',
            ]);
        }
        /** @var \Minds\Core\SendWyre\Manager $manager */
        $manager = Di::_()->get('SendWyre\Manager');
        try {
            $account = (new SendWyreAccount())
                ->setUserGuid($userGuid);

            $result = $manager->delete($account);

            return Factory::response([
                    'status' => 'success',
                    'done' => true,
                ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
