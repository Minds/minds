<?php

class MindsScraper extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "scraper";
		$this->attributes['access_id'] = ACCESS_PUBLIC; //scrapers must all show in scrapers list. 
	}

}