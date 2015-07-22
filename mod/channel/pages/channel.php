<?php
/**
 * Channel profile page
 */
namespace minds\plugin\channel\pages;

use Minds\Core;
use minds\interfaces;
use minds\entities;

use ElggMenuItem;

class channel extends core\page implements interfaces\page{
	
	public $context = 'channel';
	
	public function get($pages){
        
        if (isset($pages[0])) {
			$username = $pages[0];
			$user = get_user_by_username($username);
			if($user && $user->enabled != "no"){
				elgg_set_page_owner_guid($user->guid);
			} else {
				return $this->notfound();
				
			}
		}else{
			return $this->notfound();
		}
		
		// short circuit if invalid or banned username
		if (!$user || ($user->isBanned() && !elgg_is_admin_logged_in())) {
			register_error(elgg_echo('channel:notfound'));
			forward();
		}
	
		$action = NULL;
		if (isset($pages[1])) {
			$action = $pages[1];
		}
	
		if ($action == 'edit') {
			// use the core profile edit page
			$base_dir = elgg_get_root_path();
			require "{$base_dir}mod/channel/pages/edit.php";
			return true;
		}
		
		$carousels = core\entities::get(array('subtype'=>'carousel', 'owner_guid'=>$user->guid));
		$carousel = elgg_view('carousel/carousel', array('items'=>$carousels));
	
		$post = elgg_view_form('activity/post', array('action'=>'newsfeed/post', 'enctype'=>'multipart/form-data', 'class'=> elgg_get_logged_in_user_guid() == $user->guid ? 'enable-social-share' : ''),array('to_guid'=>$user->guid));
		$class ='';
		$sidebar = '';
		switch($pages[1]){
        case 'disable':
                if(!$user->canEdit()){
                    return true;
                }
                $user->enabled = 'no';
                $user->save();
                logout();

                return $this->notfound();
                break;
            case 'custom':
                if(!$user->canEdit()){
                    return true;
                }
				$content .= elgg_view_form('channel/custom', array('enctype' => 'multipart/form-data'), array('entity' => $user));
				break;
			case 'avatar':
				$content .= '<div class="avatar-page">';
				$content .= elgg_view('core/avatar/upload', array('entity' => $user));
				// only offer the crop view if an avatar has been uploaded
				if (isset($user->icontime)) {
					$content .= elgg_view('core/avatar/crop', array('entity' => $user));
				}
				$content .= '</div>';
				break;
			case 'about':
				$content = elgg_view('channel/about', array('user'=>$user));
				break;
			case 'blog':
			case 'blogs':
				if($user->guid == elgg_get_logged_in_user_guid())
					$content .= elgg_view('output/url', array('class'=>'elgg-button elgg-button-action', 'style'=>'margin-left:48px;', 'href'=>elgg_get_site_url() . 'blog/add', 'text'=>'Add new blog', 'title'=>'New blog'));
					
				$content .= elgg_list_entities(array(	
												'type'=>'object', 
												'subtype'=>'blog', 
												'owner_guid'=>$user->guid, 
												'limit'=>8, 
												'offset'=>get_input('offset',''),
												'full_view'=>false,
												'list_class' => 'x2'
												));			
				break;
			case 'archive':
				$subtype = 'archive';
				$subtype_input = get_input('subtype');
				if($subtype_input && in_array($subtype_input, array('video', 'image', 'album')))
					$subtype = $subtype_input;
					
				$content .= elgg_view('channel/archive_filter', array('user'=>$user, 'subtype'=>$subtype));
				$content .= elgg_list_entities(array(	
					'type'=>'object', 
					'subtype'=> $subtype, 
					'owner_guid'=>$user->guid, 
					'limit'=>8, 
					'offset'=>get_input('offset',''),
					'full_view'=>false,
					'list_class' => 'x2'
				));	
				break;
			case 'widgets':			
				// main profile page
		        $params = array(
		                'num_columns' => 2,
		                'widgets' => elgg_get_widgets($user->guid, $context),
	                	'context' => 'channel',
	            		'exact_match' => $exact_match,
		        );
		        $content .= elgg_view('page/layouts/widgets/add_button', $params);
				$content .= elgg_view('page/layouts/widgets/add_panel', $params);
		        $content .= elgg_view_layout('widgets', $params);
				break;
			case 'groups':			
				$content = elgg_list_entities_from_relationship(array(
					'full_view' => false,
					'relationship_guid' => $user->guid,
					'relationship' => 'member',
					'offset'=>get_input('offset',''),
					'list_class'=>'x2',
					//'masonry'=> false
					//'inverse_relationship' => true
				)); 
				break;
			case 'subscribers':
				$db = new \Minds\Core\Data\Call('friendsof');
				$subscribers= $db->getRow($user->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
				$users = array();
				if($subscribers){
					foreach($subscribers as $guid => $subscriber){
						if(is_numeric($subscriber)){
							//this is a local, old style subscription
							$users[] = new \minds\entities\user($guid);
							continue;
						} 
						
						$users[] = new \minds\entities\user(json_decode($subscriber,true));
					}
				}
				$content .= elgg_view_entity_list($users,array('list_class'=>'x2 users-list', 'masonry'=>false));
				break;
			case 'subscriptions':
				$db = new \Minds\Core\Data\Call('friends');
				$subscriptions = $db->getRow($user->guid, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
				$users = array();
				if($subscriptions){
					foreach($subscriptions as $guid => $subscription){
						if(is_numeric($subscription)){
							//this is a local, old style subscription
							$users[] = new \minds\entities\user($guid);
							continue;
						} 
						
						$users[] = new \minds\entities\user(json_decode($subscription,true));
					}
				}
				$content .= elgg_view_entity_list($users,array('list_class'=>'x2 users-list', 'masonry'=>false));
				break;
			case 'carousel':
				$content = elgg_view_form('carousel/batch', array('enctype'=>'multipart/form-data'), array('items'=>$carousels));
				break;
			case 'banner':
				global $CONFIG;	
				if(!$carousel){
					return false;
				}
				$carousel = $carousels[0];
				$filename = $CONFIG->dataroot . 'carousel/' . $carousel->guid . 'thin';
				header('Content-Type: image/jpeg');
				header('Expires: ' . date('r', time() + 864000));
				header("Pragma: public");
	 			header("Cache-Control: public");
				if(file_exists($filename)){
					echo file_get_contents($filename);
				} else {
					$img = imagecreatetruecolor(120, 1);
					$bg = imagecolorallocate ( $img, 50, 50, 50 );
					imagefilledrectangle($img,0,0,120,1,$bg);
					imagejpeg($img,NULL,100);
				}
				exit;
				break;
			case 'api':
				if($pages[2] == 'carousels'){
					$return = array();
					foreach($carousels as $carousel){
						$return[] = array(
										'guid' => $carousel->guid,
										'href' => $carousel->href,
										'title'=>$carousel->title,
										'shadow'=>$carousel->shadow,
										'bg' => elgg_get_site_url() . "carousel/background/$carousel->guid/$carousel->last_updated/123/thin"
									);
					}
					echo json_encode($return);
				}
				exit;
				break;
			case 'thumbs':
			case 'votes':
				$guids = \minds\plugin\thumbs\helpers\lists::getUserThumbsGuids($user, false, array('limit'=>get_input('limit', 12), 'offset'=>get_input('offset', '')));
				if($guids)
					$content .= core\entities::view(array('guids'=>$guids, 'full_view'=>false, 'list_class' => 'list-newsfeed'));
				$class = 'single-column';
				break;
			case 'news':
			case 'timeline':
			default:
				\elgg_register_plugin_hook_handler('register', 'menu:entity', array('\minds\pages\newsfeed\newsfeed', 'pageSetup'));
				//$content = elgg_list_river(array('type'=>'timeline','owner_guid'=>'personal:'.$user->guid, 'list_class'=>'minds-list-river'));
				$entities = \Minds\Core\entities::get(array(
					'type' => 'activity',
					'limit' => get_input('limit', 5),
                    'offset' => get_input('offset', ""),
                    'masonry' => false,
					'prepend' => elgg_is_logged_in() && $user->guid == elgg_get_logged_in_user_guid() ? $post : '',
					'list_class' => 'list-newsfeed',
					'owner_guid' => $user->guid
                ));
                if($entities)
                    $content .= elgg_view_entity_list($entities, array('list_class' => 'list-newsfeed','masonry' => false,'prepend' => elgg_is_logged_in() && $user->guid == elgg_get_logged_in_user_guid() ? $post : ''));
                else
                    $content = $post;
				$class = 'landing-page';
				$sidebar = elgg_view('channel/thumbs', array('user'=>$user));
				if(!$sidebar)
					$class = 'single-column';
		}
		
		$body = elgg_view_layout('two_sidebar', array(
			'content' => $content, 
			'class'=>$class,
			'header'=>$carousel, 
			'hide_ads' => true,
			'sidebar_top'=>'',
			'sidebar' => $sidebar,
			'sidebar_alt'=> elgg_view('channel/sidebar', array('user'=>$user)),
			'sidebar-alt-class' =>  'minds-fixed-sidebar-left'
		));
		
		echo elgg_view_page($user->name, $body, 'default', array('class'=>'channel grey-bg'));

	}
	
	public function post($pages){}

	public function put($pages){}

	public function delete($pages){}

	public function notfound(){
	    header("HTTP/1.0 404 Not Found");
            $buttons = \elgg_view('output/url', array('onclick'=>'window.history.back()', 'text'=>'Go back...', 'class'=>'elgg-button elgg-button-action'));
            $header = <<<HTML
<div class="elgg-head clearfix">
        <h2>404</h2>
        <h3>Ooooopppsss.... we couldn't find the page you where looking for! </h3>
        <div class="front-page-buttons">
                $buttons
        </div>
</div>
HTML;
             $body = \elgg_view_layout( "one_column", array(
                 'content' => null,
                 'header'=>$header
             ));
             echo \elgg_view_page('404', $body);
	}	
}
