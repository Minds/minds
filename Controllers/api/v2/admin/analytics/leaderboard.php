<?php


namespace Minds\Controllers\api\v2\admin\analytics;

use Minds\Core\Analytics\Manager;
use Minds\Entities\User;
use Minds\Interfaces;
use Minds\Api\Factory;

class leaderboard implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
                'status'=>'error', 
                'message'=> "You must send a type ('actors' or 'beneficiaries')"
            ]);
        }

        $type = $pages[0];

        if($type !== 'actors' && $type !== 'beneficiaries') {
            $type = 'actors';
        }

        if (!isset($pages[1])) {
            return Factory::response([
                'status'=>'error',
                'message'=> "You must send a metric"
            ]);
        }

        $metric = $pages[1] ?: 'vote:up';

        $from = $_GET['from'] * 1000 ?: strtotime('1 week ago') * 1000;
        $to = $_GET['to'] * 1000 ?: time() * 1000;

        $manager = new Manager();
        $manager->setFrom($from)
            ->setTo($to)
            ->setMetric($metric);

        switch($type) {
            case 'actors':
                $manager->setTerm('user_guid')
                    ->useUniques(false);
                break;
            case 'beneficiaries':
                switch ($metric) {
                    case "subscribe":
                    case "referral":
                        $manager->setTerm('entity_guid');
                        break;
                    default:
                        $manager->setTerm('entity_owner_guid');
                }
                break;
        }
        $result = $manager->getTopCounts();

        $counts[$type] = array_map(function($item) {
            $item['user'] = (new User($item['user_guid']))->export();
            return $item;
        }, $result);

        return Factory::response(['counts' => $counts]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}
