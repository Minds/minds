<?php
/*
 * Archive upload selection page
 *
 */

try{
	$kmodel = KalturaModel::getInstance();

	$ks = $kmodel->getClientSideSession();
	$serviceUrl = elgg_get_plugin_setting('kaltura_server_url', 'archive');
	$partnerId = elgg_get_plugin_setting('partner_id', 'archive');
}catch(Exception $e){
}
$serverUrl = elgg_get_site_url();

$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());
$albums = elgg_get_entities(array(
    'type' => 'object',
    //'container_guid' => $container_guid, 
	'subtype' => 'album',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'limit' => 0
));
//var_dump($albums);
$album = $albums[0];
//if the album cant be found then lets create one
if (!$album) { 
	$album = new TidypicsAlbum();
	$album->owner_guid = elgg_get_logged_in_user_guid();
	$album->container_guid = $container_guid;
	$album->title = 'Uploads';
	$album->access_id = 2;
	$album->uploads = true;

	if (!$album->save()) {
		register_error(elgg_echo("album:error"));
		forward(REFERER);
	}
}

$albumRes = array();

foreach($albums as $album){
	$guid = $album->guid;
    	$albumRes[$guid]['title'] = $album->title;
    	$albumRes[$guid]['id'] = (string) $guid;
}
?>

<script>
    var ks = "<?php echo $ks?>";
    var serviceUrl = "<?php echo $serviceUrl ?>";
    var partnerId = "<?php echo $partnerId ?>";
    var serverUrl = "<?php echo $serverUrl ?>";
    var albums = <?php echo json_encode($albumRes) ?>;
    var cdnUrl = "<?php echo $serverUrl ?>";
</script>

<div ng-app="mindsUploader">
    <ng-view></ng-view>
</div>
