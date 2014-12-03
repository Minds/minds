<?php
/**
 * Minds main page controller
 */
namespace minds\pages;

use minds\core;
use minds\interfaces;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;

class assets extends core\page implements interfaces\page{
	
	/**
	 * Get requests
	 */
	public function get($pages){
		$js = new AssetCollection(array(
		    new GlobAsset(elgg_get_site_entity()->path . 'vendor/*/*')
		));

		// the code is merged when the asset is dumped
		echo $js->dump();
	}
	
	public function post($pages){
	}
	
	public function put($pages){
	}
	
	public function delete($pages){
	}
	
}
