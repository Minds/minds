<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");

$type = get_input('type','latest');
$offset = get_input('offset',0);
$total = get_input('total',4);

$area = '';

set_context('search');
switch ($type) {
  case 'played':
    $videos = elgg_get_entities_from_metadata(array('order_by_metadata' => array("name" => "kaltura_video_plays", "direction" => "DESC", "as" => "integer") , 'types' => 'object', 'subtypes' => 'kaltura_video','offset'=>$offset,'limit'=>$total));
    $area = elgg_view_entity_list($videos,0,$offset,$total,false,false,false);
    break;

  case 'commented':
    $videos = __get_entities_from_annotations_calculate_x('count','object','kaltura_video','generic_comment','','',0,$total,$offset,'desc');
    $area = elgg_view_entity_list($videos,0,$offset,$total,false,false,false);
    break;

  case 'rated':
    $videos = elgg_get_entities_from_annotations(array('order_by_annotation' => array("name" => "kaltura_video_rating", "direction" => "DESC", "as" => "integer") , 'types' => 'object', 'subtypes' => 'kaltura_video','offset'=>$offset,'limit'=>$total));
    $area = elgg_view_entity_list($videos,0,$offset,$total,false,false,false);
    break;

  default:
     //grab the latest videos
     //created in elgg:
	//$area = elgg_list_entities(array('type' => 'object', 'subtype' => 'kaltura_video', 'offset' => $offset, 'limit' => $total, 'full_view' => false, 'view_type_toggle' => false, 'pagination' => false));
	//created in kaltura:
    $videos = elgg_get_entities_from_metadata(array('order_by_metadata' => array("name" => "kaltura_video_time_created", "direction" => "DESC", "as" => "integer") , 'types' => 'object', 'subtypes' => 'kaltura_video','offset'=>$offset,'limit'=>$total));
    $area = elgg_view_entity_list($videos,0,$offset,$total,false,false,false);
    break;
}

echo $area;

?>
