<?php
/**
 * Created by Roy Cohen.
 * User: root
 * Date: 7/22/13
 * Time: 9:59 AM
 * To change this template use File | Settings | File Templates.
 */

// Get variables
$title = get_input("title");
$desc = get_input("description");
$access_id = (int) get_input("privacy");
$license = get_input("license");
$tags = get_input("tags");
$mime_type = get_input("fileType");
$entityId = get_input("guid", null);
$container_guid = elgg_get_logged_in_user_guid();
$user_guid = $_SESSION['user']->getGUID();

$albums= array();
//find the users uploads album
$albums = elgg_get_entities_from_metadata(array(
    'type'=> 'object',
    'subtype' => 'album',
    'owner_guid' => elgg_get_logged_in_user_guid()
//        'metadata_name_value_pairs' => array('name'=>'uploads', 'value'=>true)

));

echo json_encode($albums);
exit;

