<?php
/**
 * Elgg relationships.
 * Stub containing relationship functions, making import and export easier.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Relationship
 */

/**
 * Convert a database row to a new ElggRelationship
 *
 * @param stdClass $row Database row from the relationship table
 *
 * @return ElggRelationship|stdClass
 * @access private
 */
function row_to_elggrelationship($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	return new ElggRelationship($row);
}

/**
 * Return a relationship.
 *
 * @param int $id The ID of a relationship
 *
 * @return ElggRelationship|false
 */
function get_relationship($id) {
	global $CONFIG;
	return;
}

/**
 * Delete a specific relationship.
 *
 * @param int $id The relationship ID
 *
 * @return bool
 */
function delete_relationship($id) {
	global $CONFIG;

	$id = (int)$id;

	$relationship = get_relationship($id);

	if (elgg_trigger_event('delete', 'relationship', $relationship)) {
		return delete_data("delete from {$CONFIG->dbprefix}entity_relationships where id=$id");
	}

	return FALSE;
}

/**
 * Define an arbitrary relationship between two entities.
 * This relationship could be a friendship, a group membership or a site membership.
 *
 * This function lets you make the statement "$guid_one is a $relationship of $guid_two".
 *
 * @param int    $guid_one     First GUID
 * @param string $relationship Relationship name
 * @param int    $guid_two     Second GUID
 *
 * @return bool
 */
function add_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$time = time();

	// Check for duplicates
	if (check_entity_relationship($guid_one, $relationship, $guid_two)) {
		//this is cassandra, why would it duplicate?
//		return false;
	}

	$db = new Minds\Core\Data\Call('relationships');
	if( $db->insert($guid_one . ':' . $relationship, array($guid_two=>$time)) 
		&&  
		$db->insert($guid_two . ':' . $relationship . ':inverted', array($guid_one=>$time)))
		{
			return true;
		}
	
	return false;
}

/**
 * Determine if a relationship between two entities exists
 * and returns the relationship object if it does
 *
 * @param int    $guid_one     The GUID of the entity "owning" the relationship
 * @param string $relationship The type of relationship
 * @param int    $guid_two     The GUID of the entity the relationship is with
 *
 * @return object|false Depending on success
 */
function check_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$db = new Minds\Core\Data\Call('relationships');
	$result = $db->getRow($guid_one . ':' . $relationship); 
	if(isset($result[$guid_two])){
		return true;
	}

	$result = $db->getRow($guid_two . ':' . $relationship . ':inverted');
	if(isset($result[$guid_one])){
                return true;
        } 
	
	return false;
}

/**
 * Remove an arbitrary relationship between two entities.
 *
 * @param int    $guid_one     First GUID
 * @param string $relationship Relationship name
 * @param int    $guid_two     Second GUID
 *
 * @return bool
 */
function remove_entity_relationship($guid_one, $relationship, $guid_two) {
	global $CONFIG;

	$obj = check_entity_relationship($guid_one, $relationship, $guid_two);
	if ($obj == false) {
		return false;
	}
	
	$db = new Minds\Core\Data\Call('relationships');
	
	
	if( ($db->removeAttributes($guid_one . ':' . $relationship, array($guid_two))!==false)
		||
		($db->removeAttributes($guid_two . ':' . $relationship . ':inverted', array($guid_one))!==false))
		{
			return true;
		} 

	return false;
}

/**
 * Removes all arbitrary relationships originating from a particular entity
 *
 * @param int    $guid_one     The GUID of the entity
 * @param string $relationship The name of the relationship (optional)
 * @param bool   $inverse      Whether we're deleting inverse relationships (default false)
 * @param string $type         The type of entity to the delete to (defaults to all)
 *
 * @return bool Depending on success
 */
function remove_entity_relationships($guid_one, $relationship = "", $inverse = false, $type = '') {
	global $CONFIG;

	$db = new Minds\Core\Data\Call('relationships');
	$ofs = $db->getRow($guid_one . ':'. $relationship);
	
	//remove the ofs first
	foreach($ofs as $of){
		$db->removeAttributes($of . ':'. $relationship . ':inverted', array($guid_one));
	}
	
	$db->removeRow($guid_one . ':' . $relationship);
	return $db->getRow($guid_one . ':' . $relationship) == FALSE;
}

/**
 * Get all the relationships for a given guid.
 *
 * @param int  $guid                 The GUID of the relationship owner
 * @param bool $inverse_relationship Inverse relationship owners?
 *
 * @return ElggRelationship[]
 */
function get_entity_relationships($guid, $inverse_relationship = FALSE) {
	global $CONFIG;
	return; //no cassandra like... it could be, but I have no reason to implement. 
}

/**
 * Return entities matching a given query joining against a relationship.
 * Also accepts all options available to elgg_get_entities() and
 * elgg_get_entities_from_metadata().
 *
 * To ask for entities that do not have a particulat relationship to an entity,
 * use a custom where clause like the following:
 *
 * 	$options['wheres'][] = "NOT EXISTS (
 *			SELECT 1 FROM {$db_prefix}entity_relationships
 *				WHERE guid_one = e.guid
 *				AND relationship = '$relationship'
 *		)";
 *
 * @see elgg_get_entities
 * @see elgg_get_entities_from_metadata
 *
 * @param array $options Array in format:
 *
 * 	relationship => NULL|STR relationship
 *
 * 	relationship_guid => NULL|INT Guid of relationship to test
 *
 * 	inverse_relationship => BOOL Inverse the relationship
 *
 * @return ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_relationship($options) {
	$defaults = array(
		'relationship' => NULL,
		'relationship_guid' => NULL,
		'inverse_relationship' => FALSE,
		'count' => FALSE,
		'offset' => '',
		'limit' => 12
	);
	
	$options = array_merge($defaults, $options);
	
	if($options['limit'] == 0){
		$options['limit'] = 9999999;
	}
	
	$id = $options['relationship_guid'] . ':' . $options['relationship'];
	//construct the relationship id from the options..
	if($options['inverse_relationship']){
		$id .= ':inverted'; //ie. friends inverted is friends:inverted. invitiations inverted is invitations:inverted
	}
	
	$db = new Minds\Core\Data\Call('relationships');
	if(!$options['count']){
		$guids = $db->getRow($id, array('offset'=>$options['offset'], 'limit'=>$options['limit']));
	} else {
		return $db->countRow($id);
	}

	//if(isset($guids[$options['offset']]))
	//	return false;

	if($guids){
		$options['guids'] = array_keys($guids); 
		return elgg_get_entities($options);
	} 
	
	return false;
}

/**
 * Returns sql appropriate for relationship joins and wheres
 *
 * @todo add support for multiple relationships and guids.
 *
 * @param string $column               Column name the guid should be checked against.
 *                                     Provide in table.column format.
 * @param string $relationship         Relationship string
 * @param int    $relationship_guid    Entity guid to check
 * @param bool $inverse_relationship Inverse relationship check?
 *
 * @return mixed
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_relationship_where_sql($column, $relationship = NULL,
$relationship_guid = NULL, $inverse_relationship = FALSE) {

	return;
}

/**
 * Returns a viewable list of entities by relationship
 *
 * @param array $options
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_relationship()
 *
 * @return string The viewable list of entities
 */
function elgg_list_entities_from_relationship(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_relationship');
}

/**
 * Gets the number of entities by a the number of entities related to them in a particular way.
 * This is a good way to get out the users with the most friends, or the groups with the
 * most members.
 *
 * @param array $options An options array compatible with
 *                       elgg_get_entities_from_relationship()
 * @return ElggEntity[]|mixed int If count, int. If not count, array. false on errors.
 * @since 1.8.0
 */
function elgg_get_entities_from_relationship_count(array $options = array()) {
	$options['count'] = true;
	return elgg_get_entities_from_relationship($options);
}

/**
 * Returns a list of entities by relationship count
 *
 * @see elgg_get_entities_from_relationship_count()
 *
 * @param array $options Options array
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_entities_from_relationship_count($options) {
	return elgg_list_entities($options, 'elgg_get_entities_from_relationship_count');
}

/**
 * Sets the URL handler for a particular relationship type
 *
 * @param string $relationship_type The relationship type.
 * @param string $function_name     The function to register
 *
 * @return bool Depending on success
 */
function elgg_register_relationship_url_handler($relationship_type, $function_name) {
	global $CONFIG;

	if (!is_callable($function_name, true)) {
		return false;
	}

	if (!isset($CONFIG->relationship_url_handler)) {
		$CONFIG->relationship_url_handler = array();
	}

	$CONFIG->relationship_url_handler[$relationship_type] = $function_name;

	return true;
}

/**
 * Get the url for a given relationship.
 *
 * @param int $id Relationship ID
 *
 * @return string
 */
function get_relationship_url($id) {
	global $CONFIG;

	$id = (int)$id;

	if ($relationship = get_relationship($id)) {
		$view = elgg_get_viewtype();

		$guid = $relationship->guid_one;
		$type = $relationship->relationship;

		$url = "";

		$function = "";
		if (isset($CONFIG->relationship_url_handler[$type])) {
			$function = $CONFIG->relationship_url_handler[$type];
		}
		if (isset($CONFIG->relationship_url_handler['all'])) {
			$function = $CONFIG->relationship_url_handler['all'];
		}

		if (is_callable($function)) {
			$url = call_user_func($function, $relationship);
		}

		if ($url == "") {
			$nameid = $relationship->id;

			$url = elgg_get_site_url()  . "export/$view/$guid/relationship/$nameid/";
		}

		return $url;
	}

	return false;
}

/**** HELPER FUNCTIONS FOR RELATIONSHIPS OF TYPE 'ATTACHED' ****/
// @todo what is this?

/**
 * Function to determine if the object trying to attach to other, has already done so
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return bool
 * @access private
 */
function already_attached($guid_one, $guid_two) {
	if ($attached = check_entity_relationship($guid_one, "attached", $guid_two)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to get all objects attached to a particular object
 *
 * @param int    $guid Entity GUID
 * @param string $type The type of object to return e.g. 'file', 'friend_of' etc
 *
 * @return ElggEntity[]
 * @access private
 */
function get_attachments($guid, $type = "") {
	$options = array(
					'relationship' => 'attached',
					'relationship_guid' => $guid,
					'inverse_relationship' => false,
					'type' => $type,
					'subtypes' => '',
					'owner_guid' => 0,
					'order_by' => 'time_created desc',
					'limit' => 10,
					'offset' => 0,
					'count' => false,
					'site_guid' => 0
				);
	$attached = elgg_get_entities_from_relationship($options);
	return $attached;
}

/**
 * Function to remove a particular attachment between two objects
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object to remove from $guid_one
 *
 * @return void
 * @access private
 */
function remove_attachment($guid_one, $guid_two) {
	if (already_attached($guid_one, $guid_two)) {
		remove_entity_relationship($guid_one, "attached", $guid_two);
	}
}

/**
 * Function to start the process of attaching one object to another
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return true|void
 * @access private
 */
function make_attachment($guid_one, $guid_two) {
	if (!(already_attached($guid_one, $guid_two))) {
		if (add_entity_relationship($guid_one, "attached", $guid_two)) {
			return true;
		}
	}
}

/**
 * Handler called by trigger_plugin_hook on the "import" event.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @access private
 */
function import_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDRelationship) {
		$tmp = new ElggRelationship();
		$tmp->import($element);
	}
	return $tmp;
}

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook return value
 * @param array  $params      Parameters
 *
 * @elgg_event_handler export all
 * @return mixed
 * @throws InvalidParameterException
 * @access private
 */
function export_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];

	$result = get_entity_relationships($guid);

	if ($result) {
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * Notify user that someone has friended them
 *
 * @param string $event  Event name
 * @param string $type   Object type
 * @param mixed  $object Object
 *
 * @return bool
 * @access private
 */
function relationship_notification_hook($event, $type, $object) {
	/* @var ElggRelationship $object */
	$user_one = get_entity($object->guid_one);
	/* @var ElggUser $user_one */

	return notify_user($object->guid_two,
			$object->guid_one,
			elgg_echo('friend:newfriend:subject', array($user_one->name)),
			elgg_echo("friend:newfriend:body", array($user_one->name, $user_one->getURL()))
	);
}

// Register the import hook
elgg_register_plugin_hook_handler("import", "all", "import_relationship_plugin_hook", 3);

// Register the hook, ensuring entities are serialised first
elgg_register_plugin_hook_handler("export", "all", "export_relationship_plugin_hook", 3);

// Register event to listen to some events
elgg_register_event_handler('create', 'friend', 'relationship_notification_hook');
