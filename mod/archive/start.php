<?php
/**
* Minds Archive
* @package minds.archive
* @author Mark Harding (mark@minds.com)
**/

namespace Minds\plugin\archive;

use Minds\Api;
use Minds\Core;
use Minds\Components;

class start extends Components\Plugin{

	public function __construct(){

		//@todo make oop
		\elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
			if($row->type == 'object'){
				switch($row->subtype){
	          case 'video':
	          case 'kaltura_video':
							return new entities\video($row);
						break;
	          case 'audio':
	            return new entities\audio($row);
	            break;
						case 'image':
							return new entities\image($row);
							break;
						case 'album':
							return new entities\album($row);
							break;
						case 'video':
							return new entities\video($row);
					}
			}
		});

	  Api\Routes::add('v1/archive', "\\minds\\plugin\\archive\\api\\v1\\archive");
		Api\Routes::add('v1/archive/albums', "\\minds\\plugin\\archive\\api\\v1\\albums");
		Api\Routes::add('v1/archive/thumbnails', "\\minds\\plugin\\archive\\api\\v1\\thumbnails");

		// Register a page handler, so we can have nice URLs (fallback in case some links go to old kaltura_video)
		\elgg_register_page_handler('archive', array($this, 'pageHandler'));

	}

	/**
	 * Legacy page handler fallback..
	 * @todo move this to router format
	 */
	public function pageHandler($page) {

		global $CONFIG;

		switch($page[0]) {

			case 'thumbnail':
				$entity = get_entity($page[1]);
				if(!$entity){
					forward(elgg_get_site_url() . '_graphics/placeholder.png');
				}
				$user = $entity->getOwnerEntity(false);
				if(isset($user->legacy_guid) && $user->legacy_guid)
					$user_guid = $user->legacy_guid;
				else
					$user_guid = $user->guid;

				$user_path = date('Y/m/d/', $user->time_created) . $user_guid;

				$data_root = $CONFIG->dataroot;
				$filename = "$data_root$user_path/archive/thumbnails/$entity->guid.jpg";

				switch($entity->subtype){
					case 'image':
						if($entity->filename)
							$filename = "$data_root$user_path/$entity->filename";

						if(isset($page[2])  && $size = $page[2]){
							if(!isset($entity->batch_guid))
								$entity->batch_guid = $this->container_guid;

							$filename = "$data_root$user_path/image/$entity->batch_guid/$entity->guid/$size.jpg";
						}
						break;
					case 'album':
						//get the first image attached to this album
						$image_guids = $entity->getChildrenGuids();
						forward($CONFIG->cdn_url.'archive/thumbnail/'.current($image_guids));
						break;
					case 'video':
						if(!$entity->thumbnail){
							$cinemr = $entity->cinemr();
	            forward($cinemr::factory('media')->get($entity->cinemr_guid.'/thumbnail'));
						}
	          break;
	        case 'audio':
	          $filename = elgg_get_site_url() . 'mod/archive/graphics/wave.png';
	        	break;
				}

				if(!file_exists($filename)){
					$user_path = date('Y/m/d/', $user->time_created) . $user->guid;
					$filename = "$data_root$user_path/archive/thumbnails/$entity->guid.jpg";
				}

				$contents = @file_get_contents($filename);

				header("Content-type: image/jpeg");
				header('Expires: ' . date('r', strtotime("today+6 months")), true);
				header("Pragma: public");
				header("Cache-Control: public");
				header("Content-Length: " . strlen($contents));
				// this chunking is done for supposedly better performance
				$split_string = str_split($contents, 1024);
				foreach ($split_string as $chunk) {
					echo $chunk;
				}
				exit;
				break;
		}

	}

}
