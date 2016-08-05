<?php
namespace Minds\Controllers\api\v1\newsfeed\oembed;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

/**
 * Minds oEmbed Wrapper for SoundCloud
 */
class soundcloud implements Interfaces\Api
{
    private $http;

    public function __construct()
    {
        $this->http = Core\Di\Di::_()->get('Http\Json');
    }

    /**
     * Returns a preview of a soundcloud url
     * @param array $pages
     */
    public function get($pages)
    {
        $query = [
            'format' => 'json',
            'url' => $_GET['url'],
            'auto_play' => 'true',
            'show_comments' => 'true',
            'iframe' => 'true'
        ];

        if (isset($_GET['maxheight'])) {
            $query['maxheight'] = (int) $_GET['maxheight'];
        }

        $endpoint = 'http://soundcloud.com/oembed?' . http_build_query($query);
        
        $result = $this->http->get($endpoint);
        return Factory::response($result);
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
