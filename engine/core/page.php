<?php
/**
 * Minds page controller. All page handler should extends upon this class. 
 */
namespace minds\core;

class page extends base{
	
	public $context = NULL;
	
	public function init(){
		\elgg_set_context($this->context);
	}

	/**
	 * Render the page
	 * (in the future this will handler all pages, but for now we will pass to elgg_view_page)
	 * 
	 * @param array $params - options to pass
	 * @return string - the page. 
	 */
	public function render(array $params = array()){
		$default = array(
			'title' => NULL,
			'body' => NULL,
			'page_shell' => 'default'
		);
		$params = array_merge($default, $params);
		return \elgg_view_page($params['title'], $params['body'], $params['page_shell'], $params);
	}

	/**
	 * Forward a page
	 * 
	 * @param string $location - the url to move to
	 * @param string $reason - the reason for the move
	 * 
	 * @return void
	 */
	public function forward($location = "", $reason = 'system'){
		if (!headers_sent()) {
			if ($location === REFERER) {
				$location = $_SERVER['HTTP_REFERER'];
			}
	
			$location = \elgg_normalize_url($location);
	
			// return new forward location or false to stop the forward or empty string to exit
			$current_page = \current_page_url();
			$params = array('current_url' => $current_page, 'forward_url' => $location);
			$location = \elgg_trigger_plugin_hook('forward', $reason, $params, $location);
	
			if ($location) {
				header("Location: {$location}");
				exit;
			} else if ($location === '') {
				exit;
			}
		} else {
			throw new \SecurityException(elgg_echo('SecurityException:ForwardFailedToRedirect'));
		}
	}
}
