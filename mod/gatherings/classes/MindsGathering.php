<?php

class MindsGathering extends ElggObject {
	
	private $bblr;
	
	public function __construct($guid = null) {
		parent::__construct($guid);
		
		elgg_load_library('bblr');
		$this->bblr = new bblr\api();
	}
	
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = "gathering";
	}

	/**
	 * Creates a gathering on the babelroom server
	 */
	public function create(){
		$id = $this->bblr->createConference(array(
			'name' => $this->title,
			'description' => $this->description,
			'owner_guid' => $this->owner_guid ?: elgg_get_logged_in_user_guid()
		));
		
		if($id)
			$this->bblr_id = $id;
		
		return $this->bblr_id;
	}
	
	/**
	 * Lets a user join a gathering
	 * 
	 * @todo access control this
	 */
	public function join(ElggUser $user){
		$invite = $this->bblr->createInvitation($this->bblr_id, array(
				'first' => $user->name,
				'last' => '',
				'email' => $user->email,
				'id' => (string)$user->guid
			), 
			$user->getIconURL('large'), 
			$this->canEdit()
		);
		return $invite->token;
	}
}
