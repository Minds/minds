<?php
/**
 * Minds newsfeed feed page
 */
namespace minds\pages\newsfeed;

use minds\core;
use minds\entities;
use minds\interfaces;

class newsfeed extends core\page implements interfaces\page{
	
	public $context = 'newsfeed';
	
	public function setup($hook, $type, $return, $params) {

		if($params['entity']->type != 'activity'){
			return $return;
		}
		
		$activity = $params['entity'];
		
		foreach($return as $id => $item){
			if(in_array($item->getName(), array('edit', 'access', 'delete')))
				unset($return[$id]);
		}
	
		$options = array(
				'name' => 'remind',
				'href' => "action/minds/remind?guid=$entity->guid",
				'text' => '<span class="entypo">&#59159;</span> Remind',
				'class' => '',
				'title' => elgg_echo('minds:remind'),
				'is_action' => true,
				'priority' => 1,
			);
		$return[] = \ElggMenuItem::factory($options);

		
		elgg_load_js('lightbox');
		elgg_load_css('lightbox');
		$options = array(
					'name' => 'embed',
					'href' => "newsfeed/$activity->guid/embed",
					'text' => '<span class="entypo">&#59406;</span> Embed',
					'class' => 'elgg-lightbox',
					'title' => elgg_echo('minds:embed'),
					'is_action' => true,
					'priority' => 1,
				);
		$return[] = \ElggMenuItem::factory($options);
		
		
		$options = array(
					'name' => 'share',
					'href' => "news/$activity->guid/share",
					'text' => '<span class="entypo">&#59407;</span> Share',
					'class' => 'elgg-lightbox',
					'title' => elgg_echo('minds:share'),
					'is_action' => true,
					'priority' => 1,
				);
		$return[] = \ElggMenuItem::factory($options);
		
		if($activity->canEdit()){
			$options = array(
					'name' => 'delete',
					'href' => "newsfeed/$activity->guid/delete",
					'text' => '<span class="entypo">&#10062;</span> Delete',
					'class' => 'ajax-non-action',
					'title' => elgg_echo('delete'),
					'is_action' => false,
					'priority' => 999,
				);
			$return[] = \ElggMenuItem::factory($options);
		}
		
		return $return;
	}
	
	public function get($pages){
			
		\elgg_register_plugin_hook_handler('register', 'menu:entity', array($this, 'setup'));
		
		if(get_input('new')){
			$activity = new entities\activity();
			$activity->setTitle('This is a rich post')
					->setBlurb('and this is is the description for it. this should go to bbc when clicked')
					->setURL('https://www.bbc.co.uk/news')
					->save();
		}
		
		if(!isset($pages[0])){
			$pages[0] = 'network';
		}

		switch($pages[0]){
			case is_numeric($pages[0]):
				switch($pages[1]){
					case 'embed':
						
						$code = '<div class="minds-post" data-guid="'.$pages[0].'"></div><script async src="'.elgg_get_site_url().'js/widgets.0.js"></script>';
						echo '<p>Copy this post to your website by copying the code below</p>';
						echo elgg_view('input/text', array('value'=>$code, 'style'=>'width:640px'));
						
						echo "<h3>Preview</h3>";
						echo $code;
						echo '<script>
								$("input[type=\'text\']").select();
								$("input[type=\'text\']").on("click", function () {
		  							 $(this).select();
								});
							</script>';
						return true;
						break;
					case 'share':
						break;
					case 'delete':
						$activity = new entities\activity($pages[0]);
						if($activity->delete()){
							system_message('Success!');
						} else {
							register_error('Ooops! Try again');
						}
			
						$this->forward(REFERRER);
						break;
					default: 
						$options = array(
							'guids' => array($pages[0]),
							'limit' => 1,
							'pagination' => false,
							'prepend' => ''
						);
				}
				break;
			case 'mine':
				$options = array(
					'owner_guid' => elgg_get_logged_in_user_guid()
				);
				break;
			case 'all':
				$options = array(

				);
				break;
			case 'network':
			default:
				$options = array(
					'network' => elgg_get_logged_in_user_guid()
				);
		}
		
		$post = elgg_view_form('activity/post', array('action'=>'newsfeed/post'));
		
		$content .= core\entities::view(array_merge(array(
			'type' => 'activity',
			'masonry' => false,
			'prepend' => $post,
			'list_class' => 'list-newsfeed'
		), $options));
		
		$sidebar_left = elgg_view('channel/sidebar', array(
			'user' => elgg_get_logged_in_user_entity()
		));
		
		$sidebar_right = "welcome";
		
		$body = \elgg_view_layout('two_sidebar', array(
			'title'=>\elgg_echo('newsfeed'), 
			'content'=>$content, 
			'sidebar'=>$sidebar_right, 
			'sidebar_alt'=>$sidebar_left,
			'sidebar-alt-class' =>  'minds-fixed-sidebar-left'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	public function post($pages){

		switch($pages[0]){
			case 'post':
				$activity = new entities\activity();
				if(isset($_POST['message']))
					$activity->setMessage($_POST['message']);
				
				if(isset($_POST['title'])){
					$activity->setTitle('This is a rich post')
						->setBlurb('and this is is the description for it. this should go to bbc when clicked')
						->setURL('https://www.bbc.co.uk/news');
				}
				
				$activity->save();
				$this->forward('newsfeed');
				exit;
		}
	}
	
	public function put($pages){
		throw new \Exception('Sorry, the put method is not supported for the page');
	}
	
	public function delete($pages){
		throw new \Exception('Sorry, the delete method is not supported for the page');
	}
	
}
