<?php
namespace minds\entities;
use Minds\Core;
use Minds\Core\Data;
/**
 * Build an entity based on an array, object or guid
 */
class Factory{
	/**
	 * Build the entity
	 * @param mixed $value
	 * @return Entity
	 */
	static public function build($value){
	    if(is_numeric($value)){
	        $db = new Data\Call('entities');
		$row = $db->getRow($value);
		$row['guid'] = $value;
		return Core\entities::build((object) $row);
	    }
	}
}
