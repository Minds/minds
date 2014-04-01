<?php
/**
 * Minds Orientation
 */

class MindsOrientation{
	
	public $steps = array(
		'avatar',
		'channel', 
		'group',
		'deck',
		'multisite',
	);
	
	public function __construct(){
		
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
	public function group(){
		$vars = array();
		$this->render('group', $vars);
	}
	
	/**
	 * Setup deck
	 */
	public function deck(){
		$vars = array();
		$this->render('deck', $vars);
	}
	
	/**
	 * Setup nodes
	 */
	public function multisite(){
		$vars = array();
		$this->render('mulitiste', $vars);
	}
	
}
