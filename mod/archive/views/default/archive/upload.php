<?php
/*
 * Archive upload selection page
 *
 */

$serverUrl = elgg_get_site_url();

$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());

$albums = elgg_get_entities(array(
    'type' => 'object',
	'subtype' => 'album',
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'limit' => 0
));

?>

<script>
    var serverUrl = "<?php echo $serverUrl ?>";
    var albums = <?php echo json_encode(array_values($albums)) ?>;
    var cdnUrl = "<?php echo $serverUrl ?>";
</script>

<div ng-app="mindsUploader">
    <ng-view></ng-view>
</div>
