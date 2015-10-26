<?php
/**
 * Points Transaction entity
 */

namespace Minds\Entities\Object;

use Minds\Entities;

class Points_transaction extends Entities\Object{

	/**
	 * Initialise attributes
	 * @return void
	 */
	public function initializeAttributes(){
		parent::initializeAttributes();
		$this->attributes = array_merge($this->attributes, array(
			'subtype' => 'points_transaction',
			'owner_guid' => elgg_get_logged_in_user_guid(),
			'access_id' => 0 //private
		));
	}

    /**
     * Set the points
     * @param int points
     * @return $this
     */
    public function setPoints($points){
        $this->points = $points;
        return $this;
    }

    /**
     * Set the description
     * @param string $description
     * @return $this
     */
	public function setDescription($description){
	    $this->description = $description;
	    return $this;
    }

    /**
     * Set the entity_guid
     * @param int guid
     * @return $this
     */
     public function setEntityGuid($guid = NULL){
         if($guid)
            $this->entity_guid = $guid;
         return $this;
     }

     /**
     * Set the owner_guid
     * @param int guid
     * @return $this
     */
     public function setOwnerGuid($guid){
         $this->owner_guid = $guid;
         return $this;
     }

    public function getExportableValues() {
        return array_merge(parent::getExportableValues(), array(
            'entity_guid',
            'points'
        ));
    }

}
