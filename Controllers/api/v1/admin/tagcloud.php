<?php
/**
 * Minds Admin: Hashtags/Tag Cloud
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Search;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;


class tagcloud implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        $tagcloud = new Search\Tagcloud();

        return Factory::response([
            'tags' => $tagcloud->get(),
            'age' => $tagcloud->getAge(),
            'hidden' => array_keys($tagcloud->fetchHidden(100)),
        ]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        switch ($pages[0]) {
            case 'refresh':
                (new Search\Tagcloud())->rebuild();
                break;
        }
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $tagcloud = new Search\Tagcloud();

        $done = $tagcloud->unhide($pages[0]);

        if (!$done) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $tagcloud->rebuild();

        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $tagcloud = new Search\Tagcloud();

        $done = $tagcloud->hide($pages[0]);

        if (!$done) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $tagcloud->rebuild();

        return Factory::response([]);
    }
}
