<?php

	class Event extends ElggObject 
	{
		const SUBTYPE = "event";
		
		protected $meta_cache;
		
 		protected $meta_defaults = array(
 			"organizer" 						=> NULL,
 			"comments_on" 						=> NULL,
 			"venue" 							=> NULL,
 			"location" 							=> NULL,
 			"region" 							=> NULL,
 			"event_type" 						=> NULL,
 			"latitude" 							=> NULL,
 			"longitude" 						=> NULL,
 			"start_time" 						=> NULL,
 			"end_time" 							=> NULL,
 			"endregistration_day" 				=> NULL,
 			"with_program" 						=> NULL,
 			"registration_ended" 				=> NULL,
 			"registration_needed" 				=> NULL,
 			"register_nologin" 					=> NULL,
 			"show_attendees" 					=> NULL,
 			"notify_onsignup" 					=> NULL,
 			"max_attendees" 					=> NULL,
 			"waiting_list" 						=> NULL,
 			EVENT_MANAGER_RELATION_UNDO			=> true,
 			EVENT_MANAGER_RELATION_ATTENDING	=> true
 		);
		
		
		protected function load($guid) 
		{
			if (!parent::load($guid)) 
			{
				return false;
			}
			
			if($metadata = elgg_get_metadata(array("guid" => $guid, "limit" => false)))
			{
				if (!is_array($this->meta_cache)) 
				{
					$this->meta_cache = array();
				}
				
				foreach($metadata as $md)
				{
					$this->meta_cache[$md->name] = $md->value;
				}
			}
			return true;
		}
		
		public function get($name) 
		{
			
			if(is_array($this->meta_cache) && array_key_exists($name, $this->meta_cache)){
				return $this->meta_cache[$name];
			} elseif (array_key_exists($name, $this->meta_defaults)){
				return $this->meta_defaults[$name];
			} 
			
			return parent::get($name);				
		}		
		
		public function setMetaData($name, $value)
		{
			if(parent::setMetaData($name, $value))
			{
				if(is_array($this->meta_cache) && array_key_exists($name, $this->meta_cache))
				{
					$this->meta_cache[$name] = $value;
				}
				return true;
			}
		}
		
		public function clearMetaData($name)
		{
			if(parent::clearMetaData($name))
			{
				if(is_array($this->meta_cache) && array_key_exists($name, $this->meta_cache))
				{
					unset($this->meta_cache[$name]);
				}
				return true;
			}
			return false;
		}
		
		protected function initializeAttributes() 
		{
			parent::initializeAttributes();
			
			$this->attributes["subtype"] = self::SUBTYPE;
		}
		
		public function getURL()
		{
			return EVENT_MANAGER_BASEURL . "/event/view/" . $this->getGUID() . "/" . elgg_get_friendly_title($this->title);
		}
		
		public function setAccessToOwningObjects($access_id = null)
		{
			$this->setAccessToProgramEntities($access_id);
			$this->setAccessToRegistrationForm($access_id);
		}
		
		public function setAccessToProgramEntities($access_id = null)
		{
			if($access_id == null)
			{
				$access_id = $this->access_id;
			}
			
			if($eventDays = $this->getEventDays())
			{
				foreach($eventDays as $day)
				{
					$day->access_id = $access_id;
					$day->save();
					
					if($eventSlots = $day->getEventSlots())
					{
						foreach($eventSlots as $slot)
						{
							$slot->access_id = $access_id;
							$slot->save();
						}
					}
				}
			}
		}
		
		public function setAccessToRegistrationForm($access_id = null)
		{
			if($access_id == null)
			{
				$access_id = $this->access_id;
			}
			
			if($questions = $this->getRegistrationFormQuestions())
			{
				foreach($questions as $question)
				{
					$question->access_id = $access_id;
					$question->save();
				}
			}
		}
		
		public function hasFiles()
		{
			$files = json_decode($this->files);
			if(count($files) > 0)
			{
				return $files;
			}
			return false;
		}
		
		public function rsvp($type = EVENT_MANAGER_RELATION_UNDO, $user_guid = null, $reset_program = true)
		{
			$result = false;
			
			if($user_guid == null)
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if(!empty($user_guid))
			{
				$event_guid = $this->getGUID();
				
				// remove registrations
				if($type == EVENT_MANAGER_RELATION_UNDO)
				{
					if(!(($user = get_entity($user_guid)) instanceof ElggUser))
					{
						$user->delete();
					}
					else
					{
						if($reset_program)
						{
							if($this->with_program)
							{
								$this->relateToAllSlots(false, $user_guid);
							}
							$this->clearRegistrations($user_guid);
						}
						
						// check if currently attending
						if(check_entity_relationship($this->getGUID(), EVENT_MANAGER_RELATION_ATTENDING, $user_guid))
						{
							if(!$this->hasEventSpotsLeft() || !$this->hasSlotSpotsLeft())
							{
								if($this->getWaitingUsers())
								{
									$this->generateNewAttendee();
								}
							}
						}
					}
				}
				
				// remove current relationships
				delete_data("DELETE FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one=$event_guid AND guid_two=$user_guid");
				
				// remove river events
				if(get_entity($user_guid) instanceof ElggUser)
				{
					$params = array(
						"subject_guid" => $user_guid,
						"object_guid" => $event_guid,
						"action_type" => "event_relationship"
					);
					elgg_delete_river($params);
				}
				
				// add the new relationship
				if($type && ($type != EVENT_MANAGER_RELATION_UNDO) && (in_array($type, event_manager_event_get_relationship_options())))
				{
					if($result = $this->addRelationship($user_guid, $type))
					{
						if(get_entity($user_guid) instanceof ElggUser)
						{
							// add river events
							if($type != "event_waitinglist"){
								add_to_river('river/event_relationship/create', 'event_relationship', $user_guid, $event_guid);
							}
						}
					}
				}
				else
				{
					$result = true;
				}
				
				if($this->notify_onsignup)
				{
					$this->notifyOnRsvp($type, $user_guid);
				}
			}
			
			return $result;
		}
		
		public function hasEventSpotsLeft()
		{
			$result = false;
			
			if($this->max_attendees != '')
			{
				$attendees = $this->countAttendees();
				
				if(($this->max_attendees > $attendees))
				{
					$result = true;
				}
			}
			else
			{
				$result = true;
			}
			
			return $result;
		}
		
		public function hasSlotSpotsLeft()
		{
			$result = true;
			
			$slotsSpots = $this->countEventSlotSpots();

			if(($slotsSpots['total'] > 0) && ($slotsSpots['left'] < 1) && !$this->hasUnlimitedSpotSlots())
			{
				$result = false;
			}
			
			return $result;
		}
		
		public function openForRegistration()
		{
			$result = true;
			
			if($this->registration_ended || (!empty($this->endregistration_day) && $this->endregistration_day < time()))
			{
				$result = false;
			}
			return $result;
		}
		
		public function clearRegistrations($user_guid = null)
		{
			if($user_guid == null)
			{
				$user_guid = elgg_get_logged_in_user_guid();
			}			
			
			if($questions = $this->getRegistrationFormQuestions())
			{
				foreach($questions as $question)
				{
					$question->deleteAnswerFromUser($user_guid);
				}
			}
		}
		
		public function getRegistrationsByUser($count = false, $user_guid = null)
		{
			if($user_guid == null){
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			$entities_options = array(
				'type' => 'object',
				'subtype' => EventRegistration::SUBTYPE,
				'joins' => array("JOIN " . elgg_get_config("dbprefix") . "entity_relationships e_r ON e.guid = e_r.guid_two"),
				'wheres' => array("e_r.guid_one = " . $this->getGUID()),
				'owner_guids' => array($user_guid),
				'count' => $count
			);
			
			return elgg_get_entities($entities_options);
		}
		
		public function _getAllRegistrations($filter) {
			if($filter == 'waiting') {
				$approved = 0;
			} else {
				$approved = 1;
			}
			
			$entities_options = array(
				'type' => 'object',
				'subtype' => EventRegistration::SUBTYPE,
				'full_view' => false,
				'offset' => $offset,
				'limit' => false,
				'joins' => array(	"JOIN " . elgg_get_config("dbprefix") . "entity_relationships e_r ON e.guid = e_r.guid_two",

									//Wachtrij check dingetje
									//"JOIN " . elgg_get_config("dbprefix") . "metadata n_table on e.guid = n_table.entity_guid",
									//"JOIN " . elgg_get_config("dbprefix") . "metastrings msn on n_table.name_id = msn.id",
									//"JOIN " . elgg_get_config("dbprefix") . "metastrings msv on n_table.value_id = msv.id"
									),
				'wheres' => array(	"e_r.guid_one = " . $this->getGUID(),
									"e_r.relationship = '" . EVENT_MANAGER_RELATION_USER_REGISTERED . "'",

									//Wachtrij check dingetje
									//"(msn.string IN ('approved'))",
									//"msv.string = $approved"
								)								
			);
			
			$entities = elgg_get_entities($entities_options);
			$return['entities'] = $entities;
			$return['count'] = count($entities);
			
			return $return;
		}

		public function _getRegistrationQuestions()	{
			$entities = $this->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_REGISTRATION_QUESTION);
			
			return $entities[0];
		}
		
		public function getRegistrationData($user_guid = null, $view = false) {
			$result = false;
			
			if($user_guid == null) {
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if($view) {
				$registration_table .= '<h3>Information</h3>';
			}

			$registration_table .= '<table>';

			if(($user_guid != elgg_get_logged_in_user_guid()) && !(($user = get_entity($user_guid)) instanceof ElggUser)) {
				$registration_table .= '<tr><td><label>'.elgg_echo('user:name:label').'</label></td><td>: '.$user->name.'</td></tr>';
				$registration_table .= '<tr><td><label>'.elgg_echo('email').'</label></td><td>: '.$user->email.'</td></tr>';
			}
			
			if($registration_form = $this->getRegistrationFormQuestions()) {
				foreach($registration_form as $question) {				
					$answer = $question->getAnswerFromUser($user_guid);
					
					$registration_table .= '<tr><td><label>'.$question->title.'</label></td><td>: '.$answer->value.'</td></tr>';
				}
				
				$registration_table .= '</table>';
			
				$result = elgg_view_module('main', "", $registration_table);
			}
			
			return $result;
		}
		
		public function generateRegistrationForm($register_type = 'register') {
			$form = false;
			
			$form_body = '<ul>';
			
			if(!elgg_is_logged_in()) {
				$form_body .= '<li><label>'.elgg_echo('user:name:label').' *</label><br />
					<input type="text" name="question_name" value="'.$_SESSION['registerevent_values']['question_name'].'" class="input-text"></li>';
				
				$form_body .= '<li><label>'.elgg_echo('email').' *</label><br />
					<input type="text" name="question_email" value="'.$_SESSION['registerevent_values']['question_email'].'" class="input-text"></li>';
			}
	
			if($registration_form = $this->getRegistrationFormQuestions()) {				
				if($register_type == 'waitinglist') {
					$form_body .= '<p>'. elgg_echo('event_manager:event:rsvp:waiting_list:message') .'</p><br />';
				}
					
				foreach($registration_form as $question) {
					$sessionValue = $_SESSION['registerevent_values']['question_'.$question->getGUID()];					

					if(elgg_is_logged_in()) {
						$answer = $question->getAnswerFromUser();
					}

					$value = (($sessionValue != '')?$sessionValue:$answer->value);

					$form_body .= elgg_view('event_manager/registration/question', array('entity' => $question, 'register' => true, 'value' => $value));
				}
				
				$form_body .= '</ul>';

				$form_body = elgg_view_module('main', "", $form_body, array("id" => "event_manager_registration_form_fields"));
			}

			if($this->with_program) {
				$form_body .= $this->getProgramData(elgg_get_logged_in_user_guid(), true, $register_type);
			}
			
			if($form_body) {
				$form_body .= elgg_view('input/hidden', array('name' => 'event_guid', 'value' => $this->getGUID()));
				if($register_type == 'register') {
					$form_body .= elgg_view('input/hidden', array('name' => 'relation', 'value' => EVENT_MANAGER_RELATION_ATTENDING));
				} elseif($register_type == 'waitinglist') {
					$form_body .= elgg_view('input/hidden', array('name' => 'relation', 'value' => EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST));
				}
				
				$form_body .= elgg_view('input/hidden', array('name' => 'register_type', 'value' => $register_type));
				
				$form_body .= elgg_view('input/submit', array('value' => elgg_echo('register')));
				
				$form_body = elgg_view_module('main', "", $form_body);
								
				$form = elgg_view('input/form', array(	'id' 		=> 'event_manager_event_register', 
														'name' 		=> 'event_manager_event_register', 
														'action' 	=> elgg_get_site_url() . '/action/event_manager/event/register', 
														'body' 		=> $form_body));
			}
			
			return $form;
		}

		public function getProgramData($user_guid = null, $participate = false, $register_type = 'register') {
			$result = false;
			
			if($user_guid == null) {
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if($eventDays = $this->getEventDays()) {
				if(!$participate) {
					elgg_push_context('programmailview');
					
					$result .= elgg_view('event_manager/program/view', array('entity' => $this, 'member' => $user_guid));
										
					elgg_pop_context();
				} else {
					$result .= elgg_view('event_manager/program/edit', array('entity' => $this, 'register_type' => $register_type, 'member' => $user_guid));		
				}
				
				$result = elgg_view_module('main', "", $result);
			}
			
			return $result;
		}

		public function getProgramDataForPdf($user_guid = null, $register_type = 'register') {
			$result = false;
			
			if($user_guid == null) {
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			if($eventDays = $this->getEventDays()) {
				elgg_push_context('programmailview');
				
				$result .= elgg_view('event_manager/program/pdf', array('entity' => $this));
									
				elgg_pop_context();
				
				$result = elgg_view_module('main', "", $result);
			}
			
			return $result;
		}
		
		public function notifyOnRsvp($type, $to = null) {
			$ia = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
			if($to == null) {
				$to = elgg_get_logged_in_user_guid();
			}
			
			if($to_entity = get_entity($to)){
				// can we make nice links in the emails
				$html_email_handler_enabled = elgg_is_active_plugin("html_email_handler");
				
				// do we have a registration link
				if($type == EVENT_MANAGER_RELATION_ATTENDING) {
					if($this->registration_needed) {
						$link = EVENT_MANAGER_BASEURL . '/registration/view/?guid=' . $this->getGUID() . '&u_g=' . $to . '&k=' . md5($this->time_created . get_site_secret() . $to);
						
						$registrationLink = PHP_EOL . PHP_EOL;
						$registrationLink .= elgg_echo('event_manager:event:registration:notification:program:linktext');
						$registrationLink .= PHP_EOL . PHP_EOL;
						if($html_email_handler_enabled){
							$registrationLink .= elgg_view("output/url", array("text" => $link, "href" => $link));
						} else {
							$registrationLink .= $link;
						}
					}
				}
				
				// make the event title for in the e-mail
				if($html_email_handler_enabled) {
					$event_title_link = elgg_view("ouput/url", array("tex" => $this->title, "href" => $this->getURL()));
				} else {
					$event_title_link = $this->title;
				}
				
				// notify the onwer of the event
				$owner_subject = elgg_echo('event_manager:event:registration:notification:owner:subject');
				
				$owner_message = elgg_echo('event_manager:event:registration:notification:owner:text:' . $type, array(
					$this->getOwnerEntity()->name,
					$to_entity->name,
					$event_title_link));
				$owner_message .= $registrationLink;
				
				notify_user($this->getOwnerGUID(), $this->getGUID(), $owner_subject, $owner_message);

				// notify the attending user
				$user_subject = elgg_echo('event_manager:event:registration:notification:user:subject');
				
				$user_message = elgg_echo('event_manager:event:registration:notification:user:text:' . $type, array(
					$to_entity->name,
					$event_title_link));
				$user_message .= $registrationLink;
								
				if($to_entity instanceof ElggUser) {
					// use notification system for real users
					notify_user($to, $this->getGUID(), $user_subject, $user_message);
				} else {
					// send e-mail for non users
					$to_email = $to_entity->name . "<" . $to_entity->email . ">";
					
					$site = elgg_get_site_entity($this->site_guid);
					if($site->email){
						if($site->name){
							$site_from = $site->name . " <" . $site->email . ">";
						} else {
							$site_from = $site->email;
						}
					} else {
						// no site email, so make one up
						if($site->name){
							$site_from = $site->name . " <noreply@" . get_site_domain($site->getGUID()) . ">";
						} else {
							$site_from = "noreply@" . get_site_domain($site->getGUID());
						}
					}
					
					elgg_send_email($site_from, $to_email, $user_subject, $user_message);
				}
			}
			
			elgg_set_ignore_access($ia);			
		}
		
		public function relateToAllSlots($relate = true, $user = null) {
			if($user == null) {
				$user = elgg_get_logged_in_user_guid();
			}
			
			if($this->getEventDays()) {
				foreach($this->getEventDays() as $eventDay) {
					foreach($eventDay->getEventSlots() as $eventSlot) {
						if($relate) {
							$user->addRelationship($eventSlot->getGUID(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION);
						} else {
							delete_data("DELETE FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one='".$user."' AND guid_two='".$eventSlot->getGUID()."'");
						}
					}
				}
			}
		}
		
		public function countEventSlotSpots() {
			$spots = array();
			
			if($eventDays = $this->getEventDays()) {
				foreach($eventDays as $eventDay) {
					if($eventSlots = $eventDay->getEventSlots()) {
						foreach($eventSlots as $eventSlot) {
							$spots['total'] = ($spots['total'] + $eventSlot->max_attendees);
							$spots['left'] = ($spots['left'] + ($eventSlot->max_attendees - $eventSlot->countRegistrations()));
						}
					}
				}
			}
			return $spots;
		}

		public function hasUnlimitedSpotSlots() {
			if($eventDays = $this->getEventDays()) {
				foreach($eventDays as $eventDay) {
					if($eventSlots = $eventDay->getEventSlots()) {
						foreach($eventSlots as $eventSlot) {
							if($eventSlot->max_attendees == '' || $eventSlot->max_attendees == 0) {
								return true;
							}
						}
					}
				}
			}
		}
		
		public function getLocation($type = false) {
			$location = $this->location;
			if($type) {
				$location = str_replace(',', '<br />',$this->location);
			}
			
			return $location;
		}
		
		public function getRelationshipByUser($user_guid = null) {
			$user_guid = (int)$user_guid;
			if(empty($user_guid)) {
				$user_guid = elgg_get_logged_in_user_guid();
			}
			
			$event_guid = $this->getGUID();
			
			$row = get_data_row("SELECT * FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one=$event_guid AND guid_two=$user_guid");
			return $row->relationship;
		}

		public function getRelationships($count = false) {
			$result = false;
			
			$event_guid = $this->getGUID();
			
			if($count){
				$query = "SELECT relationship, count(*) as count FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one=$event_guid GROUP BY relationship ORDER BY relationship ASC";
			} else {
				$query = "SELECT * FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one=$event_guid ORDER BY relationship ASC";	
			}
			
			$all_relations = get_data($query);
			
			if(!empty($all_relations)){
				$result = array();
				foreach($all_relations as $row){
					$relationship = $row->relationship;
					
					if($count){
						$result[$relationship] = $row->count;
						$result["total"] += $row->count;	
					} else {
						if(!array_key_exists($relationship, $result)){
							$result[$relationship] = array();
						}
						$result[$relationship][] = $row->guid_two;
					}
				}
			}
			
			return $result;
		}
		
		public function getRegistrationFormQuestions($count = false) {
			$result = false;
			
			if($entities = event_manager_get_eventregistrationform_fields($this->getGUID(), $count)) {
				$result = $entities;
			}
			
			return $result;
		}
		
		public function isAttending($user_guid = null) {
			$result = false;
			
			if(empty($user_guid)) {
				$user_guid = elgg_get_logged_in_user_guid();
			} 
			
			$result = check_entity_relationship($this->getGUID(), EVENT_MANAGER_RELATION_ATTENDING, $user_guid);
			
			return $result;			
		}
		
		public function isWaiting($user_guid = null) {
			$result = false;
			
			if(empty($user_guid)) {
				$user_guid = elgg_get_logged_in_user_guid();
			} 
			
			$result = check_entity_relationship($this->getGUID(), EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST, $user_guid);
			
			return $result;			
		}
		
		public function getWaitingUsers() {		
			$result = false;
				
			$query = "SELECT * FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one= '".$this->getGUID(). "' AND relationship = '".EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST."' ORDER BY time_created ASC";
				
			if($waiting_users = get_data($query))
			{
				$result = array();
				foreach($waiting_users as $user)
				{
					$result[] = get_entity($user->guid_two);
				}
			}
			
			return $result;
		}
		
		public function getFirstWaitingUser() {		
			$result = false;
				
			$query = "SELECT * FROM " . elgg_get_config("dbprefix") . "entity_relationships WHERE guid_one= '".$this->getGUID(). "' AND relationship = '".EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST."' ORDER BY time_created ASC LIMIT 1";	
			if($waiting_users = get_data($query)) {
				foreach($waiting_users as $user) {
					$result = get_entity($user->guid_two);
				}
			}
			
			return $result;
		}
		
		public function generateNewAttendee() {
			$result = false;
			
			if($waiting_user = $this->getFirstWaitingUser()) {
				$rsvp = false;
				
				if($this->with_program){
					if(($waiting_for_slots = $this->getRegisteredSlotsByUser($waiting_user->getGUID()))) {
						foreach($waiting_for_slots as $slot) {
							if($slot->hasSpotsLeft()) {
								$rsvp = true;
								$waiting_user->removeRelationship($slot->getGUID(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST);
								
								$waiting_user->addRelationship($slot->getGUID(), EVENT_MANAGER_RELATION_SLOT_REGISTRATION);
							}
						}
					} elseif($this->hasEventSpotsLeft()) {
						// not waiting for slots and event has room 
						$rsvp = true;
					}
				} elseif($this->hasEventSpotsLeft()) {
					$rsvp = true;
				}
				
				if($rsvp) {
					$this->rsvp(EVENT_MANAGER_RELATION_ATTENDING, $waiting_user->getGUID(), false);
					
					notify_user(elgg_get_logged_in_user_guid(), 
								$this->getGUID(), 
								elgg_echo('event_manager:event:registration:notification:user:subject'),
								elgg_echo('event_manager:event:registration:notification:user:text:event_spotfree', array(
									$waiting_user->name,  
									$this->getURL(), 
									$this->title)
								));
					
					$result = true;
				}
			}
			
			return $result;
		}
		
		public function getRegisteredSlotsByUser($user_guid) {
			$slots = array();
			
			$data = get_data("	SELECT slot.guid FROM " . elgg_get_config("dbprefix") . "entities AS slot
								INNER JOIN " . elgg_get_config("dbprefix") . "entities AS event ON event.guid = slot.owner_guid
								INNER JOIN " . elgg_get_config("dbprefix") . "entity_relationships AS slot_user_relation ON slot.guid = slot_user_relation.guid_two
								INNER JOIN " . elgg_get_config("dbprefix") . "users_entity AS user ON user.guid = slot_user_relation.guid_one
								WHERE 	user.guid=$user_guid AND 
										slot_user_relation.relationship='".EVENT_MANAGER_RELATION_SLOT_REGISTRATION_WAITINGLIST."'
							");
			
			foreach($data as $slot) {
				$slots[] = get_entity($slot->guid);
			}
			
			return $slots;
		}
		
		public function getIcon($size = "medium", $icontime = 0) {
			if (!in_array($size, array('small','medium','large','tiny','master','topbar'))) {
				$size = 'medium';
			}
			
			if ($icontime = $this->icontime) {
				$icontime = $icontime;
			} else {
				$icontime = "default";
			}
			
			$filehandler = new ElggFile();
			$filehandler->owner_guid = $this->getOwnerGUID();
			$filehandler->setFilename("events/" . $this->getGUID() . "/" . $size . ".jpg");
			
			if($filehandler->exists()){
				return elgg_get_site_url() . "mod/event_manager/icondirect.php?lastcache=" . $icontime . "&joindate=" . $this->time_created . "&guid=" . $this->getGUID(). "&size=" . $size;
			}
		}
		
		public function getEventDays($order = 'ASC') {
			$entities_options = array(
				'type' => 'object',
				'subtype' => EventDay::SUBTYPE,
				'relationship_guid' => $this->getGUID(),
				'relationship' => 'event_day_relation',
				'inverse_relationship' => true,
				'order_by_metadata' => array(
					"name" => "date", 
					"direction" => $order 
				),
				'limit' => false
			);
		 
			return elgg_get_entities_from_relationship($entities_options);
		}
	
		
		public function isUserRegistered($userid = null, $count = true) {
			if($userid == null) {
				$userid = elgg_get_logged_in_user_guid();
			}
			
			$entities_options = array(
				'type' => 'object',
				'subtype' => EventRegistration::SUBTYPE,
				'joins' => array("JOIN " . elgg_get_config("dbprefix") . "entity_relationships e_r ON e.guid = e_r.guid_two"),
				'wheres' => array("e_r.guid_one = " . $this->getGUID()),
				'count' => $count,
				'owner_guids' => array($userid)
			);
			
			$entityCount = elgg_get_entities_from_relationship($entities_options);
			
			if($count) {
				if($entityCount > 0) {
					return true;
				}
				return false;
			} else {
				return $entityCount[0];
			}
		}
		
		public function countAttendees() {
			elgg_set_ignore_access(true);
			
			//$entities = $this->countEntitiesFromRelationship(EVENT_MANAGER_RELATION_ATTENDING);			
			
			$entities = elgg_get_entities_from_relationship(array(
				'relationship' => EVENT_MANAGER_RELATION_ATTENDING,
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => FALSE,
				'count' => TRUE,
				'site_guid' => false
			));
			
			elgg_set_ignore_access(false);
			
			return $entities;
		}
		
		public function countWaiters() {
			elgg_set_ignore_access(true);
			
			//$entities = $this->countEntitiesFromRelationship(EVENT_MANAGER_RELATION_ATTENDING);			
			
			$entities = elgg_get_entities_from_relationship(array(
				'relationship' => EVENT_MANAGER_RELATION_ATTENDING_WAITINGLIST,
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => FALSE,
				'count' => TRUE,
				'site_guid' => false
			));
			
			elgg_set_ignore_access(false);
			
			return $entities;
		}
		
		public function exportAttendees() {
			$old_ia = elgg_get_ignore_access();
			elgg_set_ignore_access(true);
			
// 			$entities = $this->getEntitiesFromRelationship(EVENT_MANAGER_RELATION_ATTENDING);
			
			$entities = elgg_get_entities_from_relationship(array(
				'relationship' => EVENT_MANAGER_RELATION_ATTENDING,
				'relationship_guid' => $this->getGUID(),
				'inverse_relationship' => FALSE,
				'count' => FALSE,
				'site_guid' => false,
				'limit' => false
			));
			
			elgg_set_ignore_access($old_ia);
			
			return $entities;
		}
	}
