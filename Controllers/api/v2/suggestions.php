<?php
namespace Minds\Controllers\api\v2;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Common\Cookie;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities;

class suggestions implements Interfaces\Api
{

    public function get($pages)
    {
        $manager = Di::_()->get('Suggestions/Manager');
        $manager = new Core\Suggestions\Manager();
        $manager
            ->setUser(Core\Session::getLoggedinUser())
            ->setType($pages[0] ?? 'user');

        $opts = [
            'limit' => $_GET['limit'] ?? 12,
            'paging-token' => $_GET['paging-token'] ?? '',
            'offset' => $_GET['offset'] ?? 0,
        ];

        $result = $manager->getList($opts);

        return Factory::response([
            'suggestions' => Factory::exportable($result),
            'load-next' => $result->getPagingToken(),
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
        switch ($pages[0] ?? 'pass') {
            case "pass":
                $pass = new Core\Suggestions\Pass\Pass;
                $pass->setUserGuid(Core\Session::getLoggedinUser()->getGuid())
                    ->setSuggestedGuid($pages[1]);
                $manager = Di::_()->get('Suggestions/Pass/Manager');
                $manager = new Core\Suggestions\Pass\Manager();
                $manager->add($pass);
                break;
        }
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
