<?php

	/**
	* This function adds a class handler to object->faq
	* Since the old FAQ didn't had a class
	*
	* @return bool
	*/
	function user_support_faq_class_update(){
		$sql = "UPDATE " . get_config("dbprefix") . "entity_subtypes";
		$sql .= " SET class = 'UserSupportFAQ'";
		$sql .= " WHERE type='object' AND subtype='" . UserSupportFAQ::SUBTYPE . "'";
	
		return update_data($sql);
	}