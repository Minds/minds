<?php
/**
 * Minds main page controller
 */

namespace Minds\Controllers;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;

class icon extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        global $CONFIG;
        $guid = $pages[0];

        if (!$guid) {
            exit;
        }


        $cacher = Core\Data\cache\factory::build('apcu');
        //if ($cached = $cacher->get("usericon:$guid")) {
        //    $join_date = $cached;
        //} else {
        $user = new Entities\User($guid);

        if (isset($user->legacy_guid) && $user->legacy_guid) {
            $guid = $user->legacy_guid;
        }
        $join_date = $user->time_created;
        //    $cacher->set("usericon:$guid", $join_date);
        //}
        $last_cache = isset($pages[2]) ? $pages[2] : time();
        $etag = $last_cache . $guid;
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
        $size = strtolower($pages[1]);
        if (!in_array($size, array('xlarge', 'large', 'medium', 'small', 'tiny', 'master', 'topbar'))) {
            $size = "medium";
        }

        //check the user is enabled
        if ($user->enabled == 'no') {
            $contents = file_get_contents(Core\Config::build()->path . "engine/Assets/avatars/default-$size.png");
            $this->returnImage($contents, $etag);
        }

        $data_root = $CONFIG->dataroot;

        $file = new \ElggFile();
        $file->owner_guid = $guid;
        $file->setFilename("profile/{$guid}{$size}.jpg");
        $file->open("read");

        $contents = $file->read();

        if (empty($contents)) {
            $contents = file_get_contents(Core\Config::build()->path . "engine/Assets/avatars/default-$size.png");
        }

        $this->returnImage($contents, $etag);
    }

    private function returnImage($contents, $etag)
    {
        if (!empty($contents)) {
            header("Content-type: image/jpeg");
            header('Expires: ' . date('r', strtotime("today+6 months")), true);
            header("Pragma: public");
            header("Cache-Control: public");
            header("Content-Length: " . strlen($contents));
            header("ETag: $etag");
            header("X-No-Client-Cache:0");
            // this chunking is done for supposedly better performance
            $split_string = str_split($contents, 1024);
            foreach ($split_string as $chunk) {
                echo $chunk;
            }
        }
        exit;
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
