<?php
/**
 * Minds deeplinks (apple universal links)
 *
 * @version 1
 * @author Martin Santangelo
 */
namespace Minds\Controllers;

use Minds\Api\Factory;
use Minds\Interfaces;

class deeplinks implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    private $applinks = [
        'applinks' => [
            'apps' => [],
            'details' => [
                [
                    'appID' => "35U3998VRZ.com.minds.mobile",
                    'paths' => [
                        '/groups/profile/*',
                        '/newsfeed/*',
                        '/blog/view/*',
                        '/channels/*',
                    ]
                ]
            ]
        ]
    ];

    /**
     * Apple universal links /apple-app-site-association
     * 
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        ob_end_clean();

        header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        echo json_encode($this->applinks);
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