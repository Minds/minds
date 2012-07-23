<?php 

	class EventSlot extends ElggObject 
	{
		const SUBTYPE = "eventslot";
		
		protected function initializeAttributes() 
		{
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		public function countRegistrations()
		{
			$ia = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
			$result = elgg_get_entities_from_relationship(array(
				'relationship' => EVENT_MANAGER_RELATION_SLOT_REGISTRATION,
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => true,
				'count' => true,
				'site_guid' => false
			));
			
			elgg_set_ignore_access($ia);
			
			return $result;
		}
		
		public function hasSpotsLeft()
		{
			$result = false;
			
			if(empty($this->max_attendees) || (($this->max_attendees - $this->countRegistrations()) > 0))
			{
				$result = true;
			}
			return $result;
		}
		
		public function getWaitingUsers($count = false)
		{
			$ia = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
			if($count)
			{
				$result = $this->countEntitiesFromRelationship(EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST, true);
			}
			else
			{
				$result = $this->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST, true);
			}
			
			elgg_set_ignore_access($ia);
			
			return $result;
		}
		
		public function getEvent()
		{
			return $this->getOwnerEntity();
		}
		
		public function isUserWaiting($user_guid = null)
		{
			if(empty($user_guid))
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			return check_entity_relationship($user_guid, EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST, $this->getGUID());
		}
	}