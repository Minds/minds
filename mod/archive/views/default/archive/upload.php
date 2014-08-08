<?php
/*
 * Archive upload selection page
 *
 */

$serverUrl = elgg_get_site_url();

$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

$albums = array();
foreach(elgg_get_entities(array( 'subtype' => 'album', 'container_guid' => $container_guid, 'limit' => 0)) as $album){
	$albums[$album->guid]= array('guid'=>(string)$album->guid, 'title'=>$album->title);
}

?>

<script>
    var serverUrl = "<?php echo $serverUrl ?>";
    var albums = <?php echo json_encode($albums) ?>;
    var cdnUrl = "<?php echo $serverUrl ?>";
    var batch_guid = "<?php $g = new GUID(); echo $g->generate();?>";
    var container_guid = "<?php echo $container_guid;?>";
</script>

<div ng-app="mindsUploader">
    <ng-view></ng-view>
</div>
