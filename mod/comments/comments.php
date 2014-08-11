<?php
/**
 * Minds Comments
 */
 
namespace minds\plugin\comments;

use minds\core;

class comments extends \ElggPlugin{
	
	/**
	 * Initialise the plugin
	 */
	public function init(){
		core\router::registerRoutes($this->registerRoutes());
		\elgg_register_plugin_hook_handler('comments', 'all', array($this, 'displayHook'));
		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			//var_dump($row);
			if($row->type == 'comment')
				return new entities\comment($row);
		});
		
		\elgg_register_plugin_hook_handler('register', 'menu:comments', array($this,'menu'));
		
		core\resources::registerView('comments', 'minds_comments');
		core\resources::load('comments');
		
		core\resources::registerView('comments', 'minds_comments', 'js', 'footer');
		core\resources::load('comments', 'js');

	}
	
	/**
	 * Register page routes for comments (replaces actions)
	 * @return array
	 */

	public function registerRoutes(){
		$path = "minds\\plugin\\comments";
		return array(
			'/comments' => "$path\\pages\\comments",
		);
	}
	 
	/**
	 * Override the default comments display
	 */
	public function displayHook($hook, $entity_type, $returnvalue, $params){ return self::display($params['entity']); }
	public function display($entity, $form=true) {
		
		$indexes = new core\data\indexes('comments');
		$guids = $indexes->get($entity->guid, array('limit'=>\get_input('limit',3), 'offset'=>\get_input('offset',''), 'reversed'=>true));
		if($guids)
			$comments = \elgg_get_entities(array('guids'=>$guids, 'limit'=>\get_input('limit',3), 'offset'=>\get_input('offset','')));
		else 
			$comments = array();

		if(\get_input('debug')){
			$db = new \minds\core\data\call('entities');
			var_dump($db->getRows($guids)); exit;	
		}	
		if($comments)
			usort($comments, function($a, $b){ return $a->time_created - $b->time_created;});

		$comments =  \elgg_view('comments/bar', array(
		    'comments'=>$comments,
		    'parent_guid'=>$entity->guid,
		    'show_form'=>$form
		));
		
		//$comments .= \elgg_view('comments/input', array(
		// 	'entity'=>$entity
		//));

		return $comments;
	}
	
	/** 
	 * Comments menu
	 */
	public function menu($hook, $type, $return, $params) {
	
		$comment = $params['comment'];
	
		unset($return);
		
		/**
		 * Delete
		 */
		 if($comment->canEdit()){
			$delete = array(
				'name' => 'delete-comment',
				'href' => "#",
				'data-guid' => $comment->guid,
				'text' => '&#10062;',
				'title' => elgg_echo('delete'),
				'class' => 'minds-comment-delete entypo',
				'priority' => 1000
			);
			$return[] = \ElggMenuItem::factory($delete);
		 }
	
		return $return;
	}
	
}
