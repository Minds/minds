<?php
/*
 * Archive upload selection page
 *
 */


$kmodel = KalturaModel::getInstance();

$ks = $kmodel->getClientSideSession();
$serviceUrl = elgg_get_plugin_setting('kaltura_server_url', 'archive');
$partnerId = $partnerId = elgg_get_plugin_setting('partner_id', 'archive');
$serverUrl = elgg_get_site_url();
$albums = $object = elgg_get_entities(array(
    'type' => 'object',
    'subtype' => 'album',
    'limit' => 0,
));

$albumRes = array();

foreach($albums as $album)
{
    $albumRes[$album['guid']]['title'] = $album['title'];
    $albumRes[$album['guid']]['id'] = $album['guid'];
}
?>

<script>
    var ks = "<?php echo $ks?>";
    var serviceUrl = "<?php echo $serviceUrl?>";
    var partnerId = "<?php echo $partnerId?>";
    var serverUrl = "<?php echo $serverUrl?>";
    var albums = <?php echo json_encode($albumRes)?>;
</script>

<div ng-app="mindsUploader">
    <ng-view></ng-view>
</div>
