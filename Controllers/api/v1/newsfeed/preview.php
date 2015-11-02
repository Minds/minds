<?php
/**
 * Minds Newsfeed API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\newsfeed;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class preview implements Interfaces\Api
{
    /**
     * Returns a preview of a url
     * @param array $pages
     *
     * API:: /v1/newsfeed/preview
     */
    public function get($pages)
    {
        $url = $_GET['url'];
        $response = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://iframely.com/iframely?uri=".$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $meta = json_decode($output, true);
        return Factory::response($meta);
    }

    public function post($pages)
    {
        return Factory::response(array());
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
