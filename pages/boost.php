<?php
/**
 * Minds boost pages
 */
namespace minds\pages;

use Minds\Core;
use Minds\Core\Boost;
use minds\interfaces;

class boost extends core\page implements interfaces\page{
	
	public function get($pages){
		
        if($pages[0] == 'admin'){
          
            if(!elgg_is_admin_logged_in())
                return false;
            
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
            
            $guids = Boost\Factory::build(ucfirst($pages[1]))->getReviewQueue($limit, $offset);
            
    		if($guids){
    		    $entities = Core\entities::get(array('guid' => $guids));
                foreach($entities as $k => $entity){
                    $entities[$k]->boost_impressions = $guids[$entity->guid];
                }
                $content = elgg_view('boost/admin', array('entities' => $entities));
            } else {
                $content = "No new boosts";
            }
    		
    		$body = \elgg_view_layout('one_sidebar', array(
    			'title'=> 'Boost Admin', 
    			'content'=>$content,
    			'sidebar_class' => 'elgg-sidebar-alt cms-sidebar-wrapper',
    			'hide_ads'=>true
    		));
    
    		elgg_extend_view('page/elements/foot', 'cms/footer');
    		
    		echo $this->render(array('body'=>$body));
        }
	}
	
	public function post($pages){

        if($pages[0] == 'admin'){
            
            Boost\Factory::build(ucfirst($pages[1]))->accept($_POST['guid'], $_POST['impressions']);
		
            $this->forward('/boost/admin');
            
        }
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
