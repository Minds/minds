<?php
/**
 * Extended class for adding new functionalities
 */
class ElggChat extends ElggObject {

	/**
	 * Set subtype to chat.
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "chat";
	}

	/**
	 * Add user as member of the chat.
	 * 
	 * @param int $user_guid
	 */
	public function addMember($user_guid) {
		return add_entity_relationship($user_guid, 'member', $this->getGUID());
	}
	
	/**
	 * Remove user from chat
	 * 
	 * - Delete the relationship
	 * - Delete annotations from the messages
	 * - Delete annotations from the chat
	 * 
	 * @param $user
	 */
	public function removeMember($user) {
		$success = remove_entity_relationship($user->getGUID(), 'member', $this->getGUID());

		$messages = elgg_get_entities_from_relationship(array(
			'relationship' => 'unread',
			'relationship_guid' => $user->getGUID(),
			'inverse_relationship' => TRUE,
			'container_guid' => $this->getGUID(),
		));
		
		// Remove annotations from messages
		foreach ($messages as $message) {
			remove_entity_relationship($message->getGUID(), 'unread', $user->getGUID());
		}
		
		// Remove unread_messages annotation from chat
		$this->resetUnreadMessageCount($user);
		
		return $success;
	}

	/**
	 * Get guids of users participating the chat.
	 * 
	 * @return array $member_guids Array of user guids
	 */
	public function getMemberGuids() {
		$member_guids = array();
		foreach ($this->getMemberEntities() as $member) {
			$member_guids[] = $member->getGUID();
		}
		return $member_guids;
	}

	/**
	 * Get user entities that are participating the chat.
	 * 
	 * @return array $members Array of ElggUser objects
	 */
	public function getMemberEntities($options = array()) {
		$defaults = array(
			'relationship' => 'member',
			'relationship_guid' => $this->getGUID(),
			'inverse_relationship' => true,
			'limit' => false,
			'offset' => 0,
		);

		$options = array_merge($defaults, $options);

		return elgg_get_entities_from_relationship($options);
	}
	
	/**
	 * Check whether user is member of the chat.
	 * 
	 * @param object $user
	 * @return boolean
	 */
	public function isMember($user = null) {
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}
		
		return in_array($user->getGUID(), $this->getMemberGuids());
	}
	
	/**
	 * Get number of messages user hasn't read yet.
	 * 
	 * @param array $options
	 * @param obj $entity If true, returns the object instead of int.
	 * @return array $num_messages
	 */
	public function getUnreadMessagesCount($options = array(), $entity = false) {
		$defaults = array(
			'annotation_names' => 'unread_messages',
			'guid' => $this->getGUID(),
		);
		
		if (!isset($options['annotation_owner_guids'])) {
			$user = elgg_get_logged_in_user_entity();
			$defaults['annotation_owner_guids'] = $user->getGUID();
		}
		
		$options = array_merge($defaults, $options);
		
		$annotations = elgg_get_annotations($options);
		
		if (isset($annotations[0])) {
			if ($entity) {
				$num_messages = $annotations[0];
			} else {
				$num_messages = $annotations[0]->value;
			}
		} else {
			$num_messages = 0;
		}
		
		return $num_messages;
	}
	
	/**
	 * Increase the number of unread messages for an user.
	 * 
	 * @param obj $user
	 * @return boolean
	 */
	public function increaseUnreadMessageCount($user) {
		// Increase the number of unread messages under the chat
		$options = array('annotation_owner_guids' => $user->getGUID());
		$num_unread = $this->getUnreadMessagesCount($options, true);
		
		if ($num_unread) {
			// Increase the value of annotation
			$num_unread->value = $num_unread->value +1;
			return $num_unread->save();
		} else {
			// Add new annotation
			return $this->annotate('unread_messages', 1, ACCESS_LOGGED_IN, $user->getGUID());
		}
	}

	/**
	 * Reset the number of unread messages for an user.
	 * 
	 * @param obj $user
	 * @return boolean
	 */
	public function resetUnreadMessageCount($user = null) {
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}
		
		return elgg_delete_annotations(array(
			'annotation_owner_guids' => $user->getGUID(),
			'annotation_names' => 'unread_messages',
			'guid' => $this->getGUID(),
		));
	}
}
