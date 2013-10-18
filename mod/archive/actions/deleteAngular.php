<?php
/**
 * Created by Roy Cohen.
 * User: root
 * Date: 7/18/13
 * Time: 2:41 PM
 * To change this template use File | Settings | File Templates.
 */

$guid = get_input('guid');
$entity = get_entity($guid);
//find the users uploads album
$albums = elgg_get_entities_from_metadata(array(
    'type'=> 'object',
    'subtype' => 'album',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'metadata_name_value_pairs' => array('name'=>'uploads', 'value'=>true)
));

if ($entity)
{
    if($entity->getSubtype() == 'kaltura_video'){
        elgg_load_library('archive:kaltura');
        try{
            $kmodel = KalturaModel::getInstance();
            $entry = $kmodel->getEntry($entity->kaltura_video_id);
            $kmodel->deleteEntry($entity->kaltura_video_id);

        } catch(Exception $e){
        }
        $entity->delete();
        forward('archive/all');
    } elseif($entity->getSubtype() == 'file') {

        $thumbnails = array($entity->thumbnail, $entity->smallthumb, $entity->largethumb);
        foreach ($thumbnails as $thumbnail) {
            if ($thumbnail) {
                $delfile = new ElggFile();
                $delfile->owner_guid = $entity->owner_guid;
                $delfile->setFilename($thumbnail);
                $delfile->delete();
            }
        }
        if($entity->delete()){
            success_message(elgg_echo('minds:archive:delete:success'));
            forward('archive/all');
        } else {
            register_error(elgg_echo('minds:archive:delete:error'));
        }
    } elseif($entity->getSubtype() == 'image' || $entity->getSubtype() == 'album'){
        if($entity->delete()){
            system_message(elgg_echo('minds:archive:delete:success'));
//            forward('archive/'.$entity->getOwnerEntity()->username);
        } else {
            register_error(elgg_echo('minds:archive:delete:error'));
        }
    }
}
else
{
    register_error(elgg_echo('minds:archive:delete:notexists'));
}

//Delete Album feature.
foreach ($album as $albums)
{
    if ($album->guid == $entity->guid)
    {
        echo $album->guid;
        $entity->delete();
        exit;
    }
}

if ($guid)
    echo $guid;
else
    echo "Delete failed";
exit;