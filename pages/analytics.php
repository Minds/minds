<?php
/**
 * Minds analytics  pages
 */
namespace minds\pages;

use Minds\Core;
use Minds\Helpers;
use minds\interfaces;

class analytics extends core\page implements interfaces\page{

    public function get($pages){


            if(!elgg_is_admin_logged_in())
                return $this->forward("/");

            $db = new Core\Data\Call('entities_by_time');

            $guids = $db->getRow("analytics:open");
            $users = Core\entities::get(array('guids'=>array_keys($guids)));

            $requests = array(
                0 => (int) Helpers\RequestMetrics::get("api", time()),
                5 => (int) Helpers\RequestMetrics::get("api", time() - 300),
                10 => (int) Helpers\RequestMetrics::get("api", time() - 600)
            );

            $rps = ($requests[0] + $requests[5] + $requests[10]) / 900; 

            $content = elgg_view('analytics/dashboard', array('users' => $users, 'requests'=>$requests, 'rps' => $rps));

            $body = \elgg_view_layout('one_sidebar', array(
                'title'=> 'Analytics',
                'content'=>$content,
                'sidebar_class' => 'elgg-sidebar-alt cms-sidebar-wrapper',
                'hide_ads'=>true
            ));

            elgg_extend_view('page/elements/foot', 'cms/footer');

            echo $this->render(array('body'=>$body));
    }

    public function post($pages){
    }

    public function put($pages){
        throw new \Exception('Sorry, the put method is not supported for the page');
    }

    public function delete($pages){
        throw new \Exception('Sorry, the delete method is not supported for the page');
    }

}
