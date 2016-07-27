<?php
namespace Minds\Entities\Object;

use Minds\Entities;

/**
 * Points Transaction Entity
 */
class Points_transaction extends Entities\Object
{
    /**
     * Initialize attributes
     * @return void
     */
    public function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'subtype' => 'points_transaction',
            'owner_guid' => elgg_get_logged_in_user_guid(),
            'access_id' => 0 //private
        ));
    }

    /**
     * Sets `points`
     * @param  int points
     * @return $this
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * Sets `description`
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Sets `entity_guid`
     * @param  int|null $guid
     * @return $this
     */
    public function setEntityGuid($guid = null)
    {
        if ($guid) {
            $this->entity_guid = $guid;
        }
        return $this;
    }

    /**
     * Sets `owner_guid`
     * @param  int guid
     * @return $this
     */
    public function setOwnerGuid($guid)
    {
        $this->owner_guid = $guid;
        return $this;
    }

    /**
     * Returns an array of which Entity attributes are exportable
     * @return array
     */
    public function getExportableValues()
    {
        return array_merge(parent::getExportableValues(), array(
            'entity_guid',
            'points'
        ));
    }
}
