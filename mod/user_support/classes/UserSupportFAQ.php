<?php

	class UserSupportFAQ extends ElggObject {
		const SUBTYPE = "faq";
		
		protected function initializeAttributes() {
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
			$this->attributes["owner_guid"] = elgg_get_config("site_guid");
			$this->attributes["container_guid"] = elgg_get_config("site_guid");
		}
		
		public function getURL(){
			return elgg_get_site_entity()->url . "user_support/faq/" . $this->getGUID() . "/" . elgg_get_friendly_title($this->title);
		}
		
		public function getIconURL($size = "medium"){
			$result = false;
			
			switch($size){
				case "tiny":
					$result = elgg_get_site_entity()->url . "mod/user_support/_graphics/faq/tiny.png";
					break;
				default:
					$result = elgg_get_site_entity()->url . "mod/user_support/_graphics/faq/small.png";
					break;
			}
			
			return $result;
		}
	}