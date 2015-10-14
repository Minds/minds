<?php
namespace Minds\plugin\blog\entities;

use Minds\Core;

class Blog extends \ElggObject {

	/**
	 * Set subtype to blog.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "blog";
	}

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'excerpt',
			'ownerObj',
			'header_bg'
		));
	}

	/**
	 * Icon URL
	 */
	public function getIconURL($size = ''){
		if($this->header_bg){
			global $CONFIG;
			$base_url = $CONFIG->cdn_url ? $CONFIG->cdn_url : elgg_get_site_url();
			$image = elgg_get_site_url() . 'fs/v1/banner/' .  $this->guid . '/'.$this->last_updated;

			return $src;
		}

		libxml_use_internal_errors(true);
		$dom = new \DOMDocument();
		$dom->strictErrorChecking = FALSE;
		$dom->loadHTML($this->description);
		$nodes = $dom->getElementsByTagName('img');
		foreach ($nodes as $img) {
			$image = $img->getAttribute('src');
		}
		$base_url = Core\Config::build()->cdn_url ? Core\Config::build()->cdn_url: elgg_get_site_url();
		$image = $base_url . 'thumbProxy?src='. urlencode($image) . '&c=2708';
		if($width){
			$image .= '&width=' . $width;
		}
		return $image;
	}

	public function export(){
		$export = parent::export();
		$export['thumbnail_src'] = $this->getIconUrl();
		return $export;
	}

}
