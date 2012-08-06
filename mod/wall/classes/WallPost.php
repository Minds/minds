<?php
/**
 * WallPost Class
 * 
 */
class WallPost extends ElggObject {

	/**
	 * Set subtype to wallpost
	 * 
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'wallpost';
		$this->attributes['access_id'] = 2;
	}

}
