<?php
namespace Minds\Entities\Object;

use Minds\Entities;

/**
 * Carousel Entity
 */
class Carousel extends Entities\Object
{
    /**
     * Initialize entity attributes
     * @return void
     */
    public function initializeAttributes()
    {
        parent::initializeAttributes();
        $this->attributes = array_merge($this->attributes, array(
            'owner_guid' => elgg_get_logged_in_user_guid(),
            'access_id' => 2,
            'subtype' => 'carousel'
        ));
    }
}
