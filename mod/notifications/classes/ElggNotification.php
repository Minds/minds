<?php
/**
 * ElggNotification Class
 * 
 */
class ElggNotification extends ElggObject {

	/**
	 * Set subtype to thewire
	 * 
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'notification';
	}

}
