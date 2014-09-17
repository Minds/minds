<?php
/**
 * Manages facebook accounts and network calls
 */
class ElggDeckFacebookPage extends ElggDeckFacebook{

	 /* Set subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "facebook_page_account";
		$this->attributes['network'] = "facebook";
	}
	
	
	/**
	 * Post
	 */
	public function post($message){
		try{
			return $this->fbObj()->api($this->id.'/feed', 'POST', array('message'=>$message));	
		}	catch(Exception $e){
			var_dump($e->getMessage()); exit;
			return $e->getMessage();
		}
	}

}
