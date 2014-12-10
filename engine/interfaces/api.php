<?php
/**
 * Minds API request interface.
 */
namespace minds\interfaces;

interface api{
	
	public function get($pages);
	
	public function post($pages);
	
	public function put($pages);
	
	public function delete($pages);
	
}
