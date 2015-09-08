<?php
namespace Minds\plugin\blog\entities;

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
			$image = elgg_get_site_url() . 'blog/header/'.$this->guid . '/'.$this->last_updated;
			$src = $base_url . 'thumbProxy?src='. urlencode($image) . '&c=2708';
			if($size)
				$src .= '&width='.$size;
			return $src;
		}
		return minds_fetch_image($this->description, $this->owner_guid, $size);
	}

}
