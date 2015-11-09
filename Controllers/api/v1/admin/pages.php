<?php
/**
 * Minds Admin: Feature
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class pages implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     *
     */
    public function get($pages)
    {

        if(isset($pages[0])){
            try{
                $page = (new Entities\Page())
                    ->loadFromGuid($pages[0])
                    ->export();
                $response = $page;
            } catch(\Exception $e){
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }
        } else {
            $pages = Core\Pages\Manager::_()->getPages();
            $response = [
                'pages' => Factory::exportable($pages)
            ];
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        if(!Core\Session::isAdmin())
        return Factory::response([
            'status' => 'error',
            'message' => 'You are not authorized'
        ]);

        if(!isset($_POST['path']) || !$_POST['path'])
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply a path'
            ]);

        $page = (new Entities\Page())
            ->setTitle($_POST['title'])
            ->setBody($_POST['body'])
            ->setMenuContainer($_POST['menuContainer'])
            ->setPath($_POST['path'])
            ->save();

        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        $response = [];
        try{
            $page = (new Entities\Page())
                ->loadFromGuid($pages[0])
                ->delete();
        } catch(\Exception $e){
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
        return Factory::response($response);
    }
}
