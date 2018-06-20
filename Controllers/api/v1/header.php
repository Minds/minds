<?php
/**
 * Minds Blog API
 *
 * @version 1
 * @author Mark Harding
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Interfaces;

class header implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/blog/:filter
     */
    public function get($pages)
    {
        $manager = new Core\Blogs\Manager();
        $headerManager = new Core\Blogs\Header();

        $blog = $manager->get($pages[0]);
        $header = $headerManager->read($blog);

        header('Content-Type: image/jpeg');
        header('Expires: ' . date('r', time() + 864000));
        header("Pragma: public");
        header("Cache-Control: public");

        try {
            echo $header->read();
        } catch (\Exception $e) { }

        exit;
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
