<?php
namespace Minds\Core\Monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;

class Admin
{
    public function __construct()
    {
    }

    public function getQueue($limit = 50, $offset = '')
    {
        $manager = Di::_()->get('Monetization\Manager');
        $collection = $manager->get([
            'type' => 'credit',
            'status' => 'inprogress',
            'limit' => $limit,
            'offset' => $offset,
        ]);

        if (!$collection) {
            return [];
        }

        $items = [];
        $user_guids = [];

        foreach ($collection as $item) {
            if ($offset && $item['guid'] == $offset) {
                continue;
            }

            $user_guids[] = (string) $item['user_guid'];
            $items[] = $item;
        }

        $users = [];

        if ($user_guids) {
            $user_entities = Core\Entities::get([ 'guids' => array_unique($user_guids) ]);

            if ($user_entities) {
                foreach ($user_entities as $user) {
                    $users[(string) $user->guid] = $user;
                }
            }
        }

        for ($i = 0; $i < count($items); $i++) {
            if (isset($users[$items[$i]['user_guid']])) {
                $items[$i]['userObj'] = $users[$items[$i]['user_guid']]->export();
            }

            $items[$i]['amount'] = (float) $items[$i]['amount'] / 100;
        }

        return $items;
    }
}
