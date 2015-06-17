<?php
/**
 * Minds boost pages
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class boost extends core\page implements interfaces\page{
	
	public function get($pages){
		
        if($pages[0] == 'admin'){
          
            if(!elgg_is_admin_logged_in())
                return $this->forward("/");
            
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

            $type = isset($_GET['type']) ? $_GET['type'] : 'Newsfeed';            
            $guids = Core\Boost\Factory::build(ucfirst($type))->getReviewQueue($limit, $offset);
            
            if($guids){
                $entities = Core\entities::get(array('guids' => array_keys($guids)));
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
            $type = isset($_POST['type']) ? $_POST['type'] : 'Newsfeed';
            if(isset($_POST['accept'])){            
                Core\Boost\Factory::build(ucfirst($type))->accept($_POST['guid'], $_POST['impressions']);
		    } elseif(isset($_POST['reject'])){
                Core\Boost\Factory::build(ucfirst($type))->reject($_POST['guid']);
                //refund the point
                \Minds\plugin\payments\start::createTransaction(Core\session::getLoggedinUser()->guid, $_POST['impressions'] / 0.25, NULL, "boost refund");
            }
            $this->forward('/boost/admin?type='.$type);
            
        }
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
