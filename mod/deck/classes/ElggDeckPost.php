<?php
/**
 * This class with either A) post to a network or B) schedule a post
 * 
 */
class ElggDeckPost extends ElggObject{

	/**
	 * Set the super subtype
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "deck_post";
	}
	
	public function setAccounts($guids){
		$account_guids = $this->account_guids ? json_decode($this->account_guids) : array();
		foreach($guids as $guid){
			if($guid !=0)
				$account_guids[] = $guid;
		}
		$this->account_guids = json_encode($account_guids);
	}
	
	public function getAccounts(){
		$account_guids = $this->account_guids ? json_decode($this->account_guids) : null;
		if(!$account_guids){
			return null;
		}
		$accounts = array();
		foreach($account_guids as $guid){
			$accounts[] = get_entity($guid);
		}
		return $accounts;
	}
	
	public function setSubAccounts($ids){
		if(!is_array($ids)){
			$id = $ids;
			$ids = array();
			$ids[] = $id;
		}
		$sub_account_guids = $this->sub_account_guids ? json_decode($this->sub_account_guids) : array();
		$accounts = array();
		foreach($ids as $id){
			if($id == 0){
				continue;
			}
			$parts = explode('/',$id);
			$sub_account_guids[] = array('parent_guid'=>$parts[0], 'id'=>$parts[1]);
		}
		$this->sub_account_guids = json_encode($sub_account_guids);
	}
	
	public function getSubAccounts(){
		$sub_account_guids = $this->sub_account_guids ? json_decode($this->sub_account_guids) : null;
		return $sub_account_guids;
	}
	
	public function doPost(){
		//if(! $this->getAccounts()){
		//	register_error('An account must be set');
	//	}
		foreach($this->getAccounts() as $account){
			$account->post($this);
		}
		foreach($this->getSubAccounts() as $sub_account){
			$parent = get_entity($sub_account->parent_guid);
			$parent->getSubAccount($sub_account->id)->post($this);
		}
	}
	
	
	public function schedulePost($ts){
		$this->ts = $ts;
		$this->save();
	}
	
	/**
	 * Returns if an attachment is set
	 * 
	 * @return bool
	 */
	public function hasAttachment(){
		if(isset($this->attachment) && $this->attachment)
			return true;
			
		return false;
	}
	
}
