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
            
            $db = new Core\Data\Call('entities_by_time');
            $db->insert("boost:newsfeed:review", array("459748446950133766"=>10, "459748420802842637"=>10, "449242984400031744"=>10, "449242823019991040"=>10, "449239562749743104"=>10, "449239410223878144"=>10, "449239191503507456"=>10));
          
            if(!elgg_is_admin_logged_in())
                return $this->forward("/");
            
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

            $type = isset($_GET['type']) ? $_GET['type'] : 'Newsfeed';            
            $guids = Core\Boost\Factory::build(ucfirst($type))->getReviewQueue($limit, $offset);
            
            if($guids){
                $entities = Core\entities::get(array('guids' => array_keys($guids)));
                $db=new Core\Data\Call('entities_by_time');
                $count = $db->countRow("boost:".strtolower($type).":review");
                foreach($entities as $k => $entity){
                    $entities[$k]->boost_impressions = $guids[$entity->guid];
                }
                $content = elgg_view('boost/admin', array('entities' => $entities, 'remaining'=>$count));
            } else {
                $content = "No new boosts";
            }
    		
    		$body = \elgg_view_layout('one_column', array(
    			'title'=> 'Boost Admin', 
    			'content'=>$content
    		));
    
            if(get_input('ajax')){
                echo $content;
                exit;
            }
    		elgg_extend_view('page/elements/foot', 'cms/footer');
    		
    		echo $this->render(array('body'=>$body, 'class'=>'boost-page'));
        }
	}
	
	public function post($pages){

        if($pages[0] == 'admin'){
            $type = isset($_POST['type']) ? $_POST['type'] : 'Newsfeed';
            if($_POST['action'] == 'accept' || isset($_POST['accept'])){
                Core\Boost\Factory::build(ucfirst($type))->accept($_POST['guid'], $_POST['impressions']);
		    } elseif(isset($_POST['reject'])){
		        echo 1;
                Core\Boost\Factory::build(ucfirst($type))->reject($_POST['guid']);
                $entity = \Minds\entities\Factory::build($_POST['guid']);
                if($entity->type == "user"){
                    $user_guid = $entity->guid;
                } else {
                    $user_guid = $entity->owner_guid;
                }
                //refund the point
                \Minds\plugin\payments\start::createTransaction($user_guid, $_POST['impressions'] / 1, NULL, "boost refund");
            }
            
            if (!elgg_is_xhr())
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
