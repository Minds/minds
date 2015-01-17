<?php
/**
 * Minds Orientation
 */

class MindsOrientation{
	
	public $steps = array(
		'avatar',
		'channel', 
		//'deck',
		'subscribe',
		//'post',
		//'group',
		//'import',
		//'revenue',
		//'multisite',
		'complete',
	);
	
	public function __construct(){
		if(Minds\Core\minds::detectMultisite() && ($key = array_search('multisite', $this->steps)) !== false) {
		    unset($this->steps[$key]);
		}
	}
	
	public function run($step){
		if(!$step && !$step = elgg_set_plugin_user_setting('orientation_last_step', $step, elgg_get_logged_in_user_guid()))
			$step = 'avatar';
		
		$this->step($step);
	}
	
	public function step($step){
		elgg_set_plugin_user_setting('orientation_last_step', $step, elgg_get_logged_in_user_guid());
		
		if ($_SERVER['REQUEST_METHOD'] === 'POST')
		    $postVars = $_POST;
		else 
			$postVars = NULL;
		return $this->$step($postVars);
	}
	
	public function nextStep($current_step){
		$position = array_search($current_step, $this->steps); // $key = 2;
		$next = $this->steps[$position+1];
		forward(elgg_get_site_url().'register/orientation/'.$next);
	}
	
	public function render($step, $vars = array()){
		if(Minds\Core\minds::detectMultisite() && $key = array_search('deck', $this->steps) !== false) {
                    unset($this->steps[$key]);
                } 	
		$content = elgg_view('orientation/register', array('step'=>$step, 'steps'=>$this->steps, 'vars'=>$vars));
		
		$body = elgg_view_layout('one_column', array('content'=>$content));
		echo elgg_view_page(null, $body);
	}
	
	/**
	 * Create an avatar
	 */
	public function avatar($postVars = NULL){
			
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('avatar');
			
			if($_FILES['avatar']['tmp_name']){
				$icon_sizes = elgg_get_config('icon_sizes');
				$owner = elgg_get_logged_in_user_entity();
				$guid = $owner->guid;
				// get the images and save their file handlers into an array
				// so we can do clean up if one fails.
				$files = array();
				foreach ($icon_sizes as $name => $size_info) {
					$resized = get_resized_image_from_uploaded_file('avatar', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);
				
					if ($resized) {
						//@todo Make these actual entities.  See exts #348.
						$file = new ElggFile();
						$file->owner_guid = $owner->guid;
						$file->setFilename("profile/{$guid}{$name}.jpg");
						$file->open('write');
						$file->write($resized);
						$file->close();
						$files[] = $file;
					} else {
						// cleanup on fail
						foreach ($files as $file) {
							$file->delete();
						}
				
						register_error(elgg_echo('avatar:resize:fail'));
						forward(REFERER);
					}
				}
				
				// reset crop coordinates
				$owner->x1 = 0;
				$owner->x2 = 0;
				$owner->y1 = 0;
				$owner->y2 = 0;
				
				$owner->icontime = time();
				$owner->save();
			} else {
				$this->nextStep('avatar');
			}
			
			//run saves etc
			
		}
		
		$vars = array();
		
		$this->render('avatar', $vars);
	}
	
	/**
	 * Setup a channel
	 */
	public function channel($postVars = NULL){

		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('channel');

			$user = elgg_get_logged_in_user_entity();
			$user->name = get_input('name');
			$user->website = get_input('website');
			$user->location = get_input('location');
			
			$user->birthday_day = get_input('birthday-day');
			$user->birthday_month = get_input('birthday-month');
			$user->bithday_year = get_input('birthday-year');
			
			$user->social_link_facebook = get_input('social_link_facebook');
			$user->social_link_twitter = get_input('social_link_twitter');
			$user->social_link_tumblr = get_input('social_link_tumblr');
			$user->social_link_linkedin = get_input('social_link_linkedin');
			$user->social_link_github = get_input('social_link_github');

			$user->save(); 			
			//run saves etc
			$this->nextStep('channel');
		}
		
		$vars = array();
		
		$this->render('channel', $vars);
	}	
	
	/**
	 * Setup groups
	 */
	public function group($postVars){
		
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('group');
			
			$group = new ElggGroup();
			$group->name = $postVars['name'];
			$group->description = $postVars['description'];
			$group->access_id = $postVars['vis'];
			$group->membership = $postVars['membership'];
			
			$guid = $group->save();
			$has_uploaded_icon = (!empty($_FILES['avatar']['type']) && substr_count($_FILES['avatar']['type'], 'image/'));

			if ($has_uploaded_icon) {
			
				$icon_sizes = elgg_get_config('icon_sizes');
			
				$prefix = "groups/" . $guid;
			
				$filehandler = new ElggFile();
				$filehandler->owner_guid = $group->owner_guid;
				$filehandler->setFilename($prefix . ".jpg");
				$filehandler->open("write");
				$filehandler->write(get_uploaded_file('avatar'));
				$filehandler->close();
				$filename = $filehandler->getFilenameOnFilestore();
			
				$sizes = array('tiny', 'small', 'medium', 'large');
			
				$thumbs = array();
				foreach ($sizes as $size) {
					$thumbs[$size] = get_resized_image_from_existing_file(
						$filename,
						$icon_sizes[$size]['w'],
						$icon_sizes[$size]['h'],
						$icon_sizes[$size]['square']
					);
				}
			
				if ($thumbs['tiny']) { // just checking if resize successful
					$thumb = new ElggFile();
					$thumb->owner_guid = $group->owner_guid;
					$thumb->setMimeType('image/jpeg');
			
					foreach ($sizes as $size) {
						$thumb->setFilename("{$prefix}{$size}.jpg");
						$thumb->open("write");
						$thumb->write($thumbs[$size]);
						$thumb->close();
					}
			
					$group->icontime = time();
					$group->save();
				}
			}
			
			
			
			
			$this->nextStep('group');
		}
		$vars = array();
		$this->render('group', $vars);
	}
	
	/**
	 * Setup deck
	 */
	public function deck($postVars){
		
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('deck');
			
			$this->nextStep('deck');
		}
		$vars = array();
		$this->render('deck', $vars);
	}
	
	/**
	 * Setup rss imports
	 */
	public function import($postVars){
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('import');
			
			$scraper = new MindsScraper();
			$scraper->title = $postVars['name'];
			$scraper->feed_url = $postVars['url'];
			$scraper->save();
			
			$this->nextStep('import');
		}
		
		$vars = array();
		$this->render('import', $vars);
	}

	/**
	 * Revenue (paypal setup)
	 */
	public function revenue($postVars){
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('revenue');
			
				$user = elgg_get_logged_in_user_entity();
				$user->paypal_email = $postVars['paypal_email'];
				$user->save();
			
			$this->nextStep('revenue');
		}
		
		$vars = array();
		$this->render('revenue', $vars);
	}

	/**
	 * Setup nodes
	 */
	public function multisite($postVars){
		if (isset($postVars)) {
				$this->nextStep('multisite');
		}
		$vars = array();
		$this->render('multisite', $vars);
	}
	
	/**
	 * Post a status
	 */
	public function post($postVars){
		if (isset($postVars)) {
			if(isset($postVars['skip']))
				$this->nextStep('post');
				
			$accounts = get_input('accounts');
			$schedule_date = get_input('schedule_date');
			$schedule_hour = get_input('schedule_time_hour');
			$schedule_minute = get_input('schedule_time_minute');
				
			$post = new ElggDeckPost();
			$post->message = $postVars['message'];
			$post->attachment = false;
			$post->to_guid = $postVars['to_guid'];
			$post->container_guid = $postVars['container_guid'];
			$post->access_id = $postVars['access_id'];
			$attachment = $_FILES['attachment'];
			
			$attach_obj = new ElggFile();
			
			if(isset($attachment['name']) && !empty($attachment['name'])){
				
				$guid = (string) new GUID();
					
				$icon_sizes = elgg_get_config('icon_sizes');
				
				foreach ($icon_sizes as $name => $size_info) {
					$resized = get_resized_image_from_uploaded_file('attachment', $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale']);
				
					if ($resized) {
						//@todo Make these actual entities.  See exts #348.
						$file = new ElggFile();
						$file->owner_guid = elgg_get_logged_in_user_guid();
						$file->setFilename("attachments/{$guid}/{$name}.jpg");
						$file->open('write');
						$file->write($resized);
						$file->close();
						//$files[] = $file;
					}
				}
				
				$post->attachment = $guid;
			}
				
			//do we have sub accounts?
			foreach($accounts as $k=>$account){
				$parts = explode('/',$account);
				if(count($parts) > 1){
					$sub_accounts[] = $account;
					unset($accounts[$k]);
				}
			}
			$post->setAccounts($accounts);
			$post->setSubAccounts($sub_accounts);
			
			//CURRENT TIME
			$time = time();
			$scheduled = strtotime($schedule_date . " $schedule_hour:$schedule_minute");
			
			//if scheduled over 5 mins into the future, schedule
			if($scheduled > ($time+300)){
				$post->schedulePost($scheduled);
				system_message('Scheduled');
			} else {
				$post->doPost();
				system_message('Message posted');
			}
			$this->nextStep('post');
		}
		$vars = array();
		$this->render('post', $vars);
	}
	
	/**
	 * Setup subscribe
	 */
	public function subscribe($postVars){
		if (isset($postVars)) {
			$this->nextStep('subscribe');
		}
		$vars = array();
		$this->render('subscribe', $vars);
	}

	/**
	 * Comepleted
	 */
	public function complete(){
		$user = elgg_get_logged_in_user_entity();
		forward('/'.$user->username);
	}
}
