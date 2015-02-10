<?php
/**
 * Elgg Object
 *
 * Elgg objects are the most common means of storing information in the database.
 * They are a child class of ElggEntity, so receive all the benefits of the Entities,
 * but also include a title and description field.
 *
 * An ElggObject represents a row from the objects_entity table, as well
 * as the related row in the entities table as represented by the parent
 * ElggEntity object.
 *
 * @internal Title and description are stored in the objects_entity table.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Object
 * 
 * @property string $title       The title, name, or summary of this object
 * @property string $description The body, description, or content of the object
 * @property array  $tags        Array of tags that describe the object
 */
class ElggObject extends ElggEntity {

	/**
	 * Initialise the attributes array to include the type,
	 * title, and description.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['type'] = "object";
		$this->attributes['title'] = NULL;
		$this->attributes['description'] = NULL;
		$this->attributes['time_created'] = time();
		$this->attributes['time_updated'] = time();
	}

	/**
	 * Load or create a new ElggObject.
	 *
	 * If no arguments are passed, create a new entity.
	 *
	 * If an argument is passed, attempt to load a full ElggObject entity.
	 * Arguments can be:
	 *  - The GUID of an object entity.
	 *  - A DB result object from the entities table with a guid property
	 *
	 * @param mixed $guid If an int, load that GUID.  If a db row, then will attempt to
	 * load the rest of the data.
	 *
	 * @throws IOException If passed an incorrect guid
	 * @throws InvalidParameterException If passed an Elgg* Entity that isn't an ElggObject
	 */
	function __construct($guid = null) {
		$this->initializeAttributes();

		// compatibility for 1.7 api.
		$this->initialise_attributes(false);
		
		parent::__construct($guid);
	}

	/**
	 * Loads the full ElggObject when given a guid.
	 *
	 * @param mixed $guid GUID of an ElggObject or the stdClass object from entities table
	 *
	 * @return bool
	 * @throws InvalidClassException
	 */
	protected function load($guid) {
		foreach($guid as $k => $v){
			$this->attributes[$k] = $v;
		}		

		cache_entity($this);
		
		return true;
	}

	/**
	 * Return sites that this object is a member of
	 *
	 * Site membership is determined by relationships and not site_guid.d
	 *
	 * @todo This should be moved to ElggEntity
	 * @todo Unimplemented
	 *
	 * @param string $subtype Optionally, the subtype of result we want to limit to
	 * @param int    $limit   The number of results to return
	 * @param int    $offset  Any indexing offset
	 *
	 * @return array|false
	 */
	function getSites($subtype = "", $limit = 10, $offset = 0) {
		return get_site_objects($this->getGUID(), $subtype, $limit, $offset);
	}

	/**
	 * Add this object to a site
	 *
	 * @param int $site_guid The guid of the site to add it to
	 *
	 * @return bool
	 */
	function addToSite($site_guid) {
		return add_site_object($this->getGUID(), $site_guid);
	}

	/*
	 * EXPORTABLE INTERFACE
	 */

	/**
	 * Return an array of fields which can be exported.
	 *
	 * @return array
	 */
	public function getExportableValues() {
		return array_merge(parent::getExportableValues(), array(
			'title',
			'description',
			'featured',
			'featured_id',
			'ownerObj',
			'category'
		));
	}

	/**
	 * Can a user comment on this object?
	 *
	 * @see ElggEntity::canComment()
	 *
	 * @param int $user_guid User guid (default is logged in user)
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0) {
		$result = parent::canComment($user_guid);
		if ($result !== null) {
			return $result;
		}

		if ($user_guid == 0) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		// must be logged in to comment
		if (!$user_guid) {
			return false;
		}

		// must be member of group
		if (elgg_instanceof($this->getContainerEntity(), 'group')) {
			if (!$this->getContainerEntity()->canWriteToContainer($user_guid)) {
				return false;
			}
		}

		// no checks on read access since a user cannot see entities outside his access
		return true;
	}

	/**
	 * Gets the ElggEntity that owns this entity.
     *
     * @return ElggEntity The owning entity
      */
	public function getOwnerEntity($brief = true) {
		global $CONFIG;
		if($brief && isset($this->ownerObj) && is_array($this->ownerObj)){
			if($this->ownerObj['name']){
				if(!$this->ownerObj['icontime'])
					$array = array_merge($this->ownerObj, array('icontime'=>$CONFIG->lastcache));
				else
					$array = $this->ownerObj;
				
				$cache = true;
				if(elgg_get_logged_in_user_guid() == $this->owner_guid)
					$cache = false;
				return new ElggUser($array, $cache);
			}  else {
				if($this->canEdit()){
	//				$this->save();
				}
			}
		}
		
		$owner = new ElggUser($this->owner_guid, false);
 		return $owner;
	}
	/**
 	 * Save an object.
	 *
	 * @return bool|int
	 * @throws IOException
	 */
	public function save($index = true) {
		//cache owner_guid for brief
		if($owner = $this->getOwnerEntity(false))
			$this->ownerObj = $owner->export();
		
		$guid = parent::save($index);
        $prepared = new Minds\Core\Data\Neo4j\Prepared\Common();
        $return = Minds\Core\Data\Client::build('Neo4j')->request($prepared->createObject($this));
        return $guid;
	}
}
	
