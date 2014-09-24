<?php
/**
 * Defacto entities controller for minds
 */
namespace minds\core;

use minds\core\data;
class entities extends base{
	
	public function init(){}
	
	
	static public function get(array $options = array()){
		return \elgg_get_entities($options);
	}
	
	static public function view($options){
		$options['count'] = NULL;
		return \elgg_list_entities($options);
	}
	
	/**
	 * Builds an entity object, based on the row
	 * 
	 * @param mixed $row
	 * @param bool $cache - cache or load from cache?
	 * @return object
	 */
	static public function build($row, $cache = true){

		if (!is_object($row)) {
			return $row;
		}

		if(!isset($row->guid)){
			return $row;
		}

		//plugins should, strictly speaking, handle the routing of entities by themselves..
		if($new_entity = elgg_trigger_plugin_hook('entities_class_loader', 'all', $row))
			return $new_entity;

		if(isset($row->subtype) && $row->subtype){
			$sub = "minds\\entities\\$row->subtype";
			if(class_exists($sub)){
				return new $sub($row, $cache);
			}
		}
		
		$default = "minds\\entities\\$row->type";
		if(class_exists($default)){
			return new $default($row, $cache);
		}

	}
	
}
