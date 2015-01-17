<?php
/**
 * Directory pages
 */
namespace minds\plugin\channel\pages;

use Minds\Core;
use Minds\Core\data;
use minds\interfaces;
use minds\entities;

class directory extends core\page implements interfaces\page{
	
	public $context = 'channel';
	

	public function get($pages){

		$title = "Directory";
		$options = array(
			'type' => 'user', 
			'full_view' => false, 
			'limit'=>$limit, 
			'masonry'=>false, 
			'list_class'=>'users-list'
		);
		$limit = get_input('limit', 12);
		$offset = get_input('offset', '');
		
		switch($pages[0]){
			
			case 'subscribers':
				$db = new data\call('friendsof');
				$subscriptions = $db->getRow($pageowner->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
				
				$users = array();
				foreach($subscribers as $guid => $subscription){
					if(is_numeric($subscribers)){
						//this is a local, old style subscription
						$users[] = new entities\user($guid);
						continue;
					} 
					$users[] = new entities\user(json_decode($subscribers,true));
				}
				
				$content = elgg_view_entity_list($users, $options, $offset, $limit, false, false,false);
				break;
				
			case 'subscriptions':
				$db = new data\call('friends');
				$subscriptions = $db->getRow($page_owner->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
				
				$users = array();
				foreach($subscriptions as $guid => $subscription){
					if(is_numeric($subscription)){
						//this is a local, old style subscription
						$users[] = new entities\user($guid);
						continue;
					} 
					
					$users[] = new entities\user(json_decode($subscription,true));
				}
				
				$content = elgg_view_entity_list($users,$options, $offset, $limit, false, false,false);
				break;
				
			case 'sites':
				$content = core\entities::view(array('subtype'=>'node', 'limit'=>$limit, 'offset'=>$offset, 'directory_output'=>true,  'list_class'=>"nodes-directory-list"));

				//$content = elgg_view('minds_nodes/directory', array('nodes'=>$nodes));
				break;
				
			case 'trending':
	       		$opts = array('timespan' => get_input('timespan', 'day'));
		        $trending = new \MindsTrending(null, $opts);
				$guids = $trending->getList(array('type'=>'user', 'limit'=>$limit, 'offset'=>(int) $offset));
				
				if($guids){
					$options['guids'] = $guids;
					$content = elgg_list_entities($options);
				}
				break;
			
			case 'newest':
                		$options['limit'] = $limit;
				$options['newest_first'] = true;
                		$content = elgg_list_entities($options);
				break;
			
			case 'featured':
			default:
				$guids = data\indexes::fetch('user:featured', array('limit'=>$limit, 'offset'=>$offset));
				if(!$guids){
					$content = ' ';
					break;
				}
				
				$options['guids'] = $guids;
				$options['load-next'] = end(array_keys($guids));		
				$content = elgg_list_entities($options);
				break;
		}
		
				
		$params = array(
			'content' => $content,
			'sidebar' => elgg_view('channels/sidebar'),
			'title' => $title,
			'filter_override' => elgg_view('channels/nav', array('selected' => $pages[0])),
			'class'=> 'channels'
		);
		
		$body = elgg_view_layout('tiles', $params);
		echo $this->render(array('body'=>$body, 'class'=>'grey-bg'));

	}
	
	public function post($pages){}

	public function put($pages){}

	public function delete($pages){}
	
}
