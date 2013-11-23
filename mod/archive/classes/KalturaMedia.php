<?php
/**
 * Kaltura Media Class 
 *
 */


class KalturaMedia extends ElggObject {
	/**
	 * Sets the internal attributes
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "kaltura_video";
	}

	/**
	 * Constructor
	 * @param mixed $guid
	 */
	public function __construct($guid = null) {
		parent::__construct($guid);
	}
	
	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'kaltura_video_id',
			'thumbnail_sec'
		));
	}

	/**
	 * Get entry
	 */
	public function getEntry(){
		$kmodel = KalturaModel::getInstance();
		return $kmodel->getEntry($this->kaltura_video_id);
	}

	/** 
	 * Return the play counts
	 */
	public function getPlayCount(){
		return $this->getEntry()->plays;
	}

}
