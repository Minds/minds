<?php
/**
 * Minds CMS Plugin
 *
 * provides content management abilities to the site.
 */

namespace minds\plugin\cms;

use Minds\Components;
use Minds\Core;

class start extends Components\Plugin{

	public function init(){

		/**
		 * Register our page end points
		 */
		$path = "minds\\plugin\\cms";
		core\Router::registerRoutes(array(
				'/p' => "$path\\pages\\page",
				'/p/header' => "$path\\pages\\header",
				'/s' => "$path\\pages\\sections",
				'/admin/cms/sections' => "$path\\pages\\sections",
			));


		\elgg_register_plugin_hook_handler('output-extend', 'index', array($this, 'index'));

	}

	/**
	 * This is the homepage extension for the section blocks
	 * @todo implements hard caching as this is essentially just static content
	 */
	public function index($hook, $type, $return, $params){

		$guids = core\Data\indexes::fetch('object:cms:sections:index', array('limit'=>1000));

		$add = '';
		if(elgg_is_admin_logged_in())
			$add = '<div class="cms-section-add"><a href="#" data-group="index"> + Add a panel </a></div>';

		if(!$guids)
			$sections = array();
		else
			$sections = core\Entities::get(array('guids'=>$guids));
		$return .= elgg_view('cms/sections', array('sections'=>$sections, 'group'=>'index'));
		$return .= $add;

		elgg_extend_view('page/elements/foot', 'cms/footer');

		return $return;
	}

	public function menuOverride($hook, $type, $return, $params){
		if(isset($params['entity']) &&  $params['entity']->subtype == 'cms_page'){

			$entity = $params['entity'];
			foreach($return as $k => $item){
				if(in_array($item->getName(), array('access', 'feature', 'thumbs:up', 'thumbs:down', 'delete')))
					unset($return[$k]);
			}

			if($entity->canEdit()){
				$options = array(
								'name' => 'edit',
								'href' => "p/edit/$entity->uri",
								'text' => 'Edit',
								'title' => elgg_echo('edit'),
								'priority' => 1,
							);
				$return[] = \ElggMenuItem::factory($options);

				$options = array(
								'name' => 'delete',
								'href' => "p/delete/$entity->uri",
								'text' => 'Delete',
								'title' => elgg_echo('delete'),
								'class'=>'ajax-non-action'
							);
				$return[] = \ElggMenuItem::factory($options);

			}
			return $return;
		}
	}

}
