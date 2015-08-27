<?php
/**
 * Minds Comments
 */

namespace minds\plugin\comments;

use Minds\Core;
use Minds\Api;

class comments extends \ElggPlugin{

	/**
	 * Initialise the plugin
	 */
	public function init(){
		core\router::registerRoutes($this->registerRoutes());
    Api\Routes::add('v1/comments', "minds\\plugin\\comments\\api\\v1\\comments");

		\elgg_register_plugin_hook_handler('comments', 'all', array($this, 'displayHook'));
		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			//var_dump($row);
			if($row->type == 'comment')
				return new entities\comment($row);
		});

    Core\Events\Dispatcher::register('export:extender', 'all', function($event){
        $params = $event->getParameters();
        $export = array();
            $cacher = Core\Data\cache\factory::build();
        $db = new Core\Data\Call('entities_by_time');
       if($params['entity']->entity_guid){
					$guid = $params['entity']->entity_guid;
        } else {
          $guid = $params['entity']->guid;
				}

				$cached = $cacher->get("comments:count:$guid");
				if($cached !== FALSE){
					$count = $cached;
				} else {
					$count = $db->countRow('comments:' . $params['entity']->entity_guid);
					$cacher->set("comments:count:$guid", $count);
				}


        $export['comments:count'] = $count;
        $event->setResponse($export);
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
			'/comments' => "$path\\pages\\comments"
		);
	}

	/**
	 * Override the default comments display
	 */
	public function displayHook($hook, $entity_type, $returnvalue, $params){ return self::display($params['entity']); }
	public function display($entity, $form=true) {

        $limit = \get_input('limit',3);
        $offset = \get_input('offset','');
        if(get_input('ajax')){
            $limit = 3;
            $offset = '';
        }

		$indexes = new core\Data\indexes('comments');
		$guids = $indexes->get($entity->guid, array('limit'=>$limit, 'offset'=>$offset, 'reversed'=>true));
		if($guids)
			$comments = \elgg_get_entities(array('guids'=>$guids, 'limit'=>$limit, 'offset'=>$offset));
		else
			$comments = array();

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

		//unset($return);

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
