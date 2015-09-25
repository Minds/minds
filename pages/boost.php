<?php
/**
 * Minds boost pages
 */
namespace minds\pages;

use Minds\Core;
use minds\interfaces;

class boost extends core\page implements interfaces\page{
    private $rate = 0.5;

	public function get($pages){
		
        if($pages[0] == 'admin'){
    
            if(!elgg_is_admin_logged_in())
                return $this->forward("/");

            $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
            $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

            $type = isset($_GET['type']) ? $_GET['type'] : 'Newsfeed';            
            $queue = Core\Boost\Factory::build(ucfirst($type))->getReviewQueue($limit, $offset);
            $count =  Core\Boost\Factory::build(ucfirst($type))->getReviewQueueCount();
            $guids = array();
            foreach($queue as $data){
                $_id = (string) $data['_id'];
                $guids[$_id] = $data['guid'];
            }
            
            if($guids){
                $entities = Core\Entities::get(array('guids' => $guids));
                $db = new Core\Data\Call('entities_by_time');

                foreach($entities as $k => $entity){
                    foreach($queue as $data){
                        if($data['guid'] == $entity->guid){
                            $entities[$k]->boost_impressions = $data['impressions'];
                            $entities[$k]->boost_id = (string) $data['_id'];
                        }
                    }
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
                Core\Boost\Factory::build(ucfirst($type))->accept($_POST['_id']);
		    } elseif($_POST['action'] == 'reject' || isset($_POST['reject'])){
		        echo 1;
                Core\Boost\Factory::build(ucfirst($type))->reject($_POST['_id']);
                $entity = \Minds\entities\Factory::build($_POST['guid']);
                if($entity->type == "user"){
                    $user_guid = $entity->guid;
                } else {
                    $user_guid = $entity->owner_guid;
                }
                //refund the point
                \Minds\plugin\payments\start::createTransaction($user_guid, $_POST['impressions'] / $this->rate, NULL, "boost refund");
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
