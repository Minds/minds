<?php 

	class EventDay extends ElggObject {
		const SUBTYPE = "eventday";
		
		protected function initializeAttributes() {
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		public function getEventSlots() {
			$entities_options = array(
				'type' => 'object',
				'subtype' => EventSlot::SUBTYPE,
				'relationship_guid' => $this->getGUID(),
				'relationship' => 'event_day_slot_relation',
				'inverse_relationship' => true,
				'order_by_metadata' => array("name" => "start_time", "as" => "interger"),
				'limit' => false
			);
		 
			return elgg_get_entities_from_relationship($entities_options);
		}
	}