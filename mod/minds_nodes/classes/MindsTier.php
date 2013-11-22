<?php
/** 
 * Class to handle a Minds Node
 */

class MindsTier extends ElggObject{


	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "minds_tier";
	}

	/**
	 * Is this node allowed it's own domain
	 */
	public function allowedDomain(){
		if($this->allowed_domain == 'yes'){
			return true;
		} else {
			return false;
		}
	}


}
