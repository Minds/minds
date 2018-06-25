<?php
namespace Minds\Controllers\api\v2\admin\rewards;

use Minds\Api\Exportable;
use Minds\Core\Rewards\Withdraw\Repository;
use Minds\Entities\User;
use Minds\Interfaces;
use Minds\Api\Factory;

class withdrawals implements Interfaces\Api, Interfaces\ApiAdminPam
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $repository = new Repository();
        $username = $_GET['user'];

        if (!$username) {
            return Factory::response([
                'withdrawals' => [],
                'load-next' => '',
            ]);
        }

        $user = new User(strtolower($username));

        $withdrawals = $repository->getList([
            'limit' => isset($_GET['limit']) ? (int) $_GET['limit'] : 12,
            'offset' => isset($_GET['offset']) ? $_GET['offset'] : '',
            'user_guid' => $user->guid
        ]);

        return Factory::response([
            'withdrawals' => Exportable::_($withdrawals['withdrawals']),
            'load-next' => (string) base64_encode($withdrawals['token']),
        ]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
