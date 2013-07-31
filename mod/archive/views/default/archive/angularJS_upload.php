<?php
/*
 * Archive upload selection page
 *
 */


$kmodel = KalturaModel::getInstance();

$ks = $kmodel->getClientSideSession();
$serviceUrl = elgg_get_plugin_setting('kaltura_server_url', 'archive');
$partnerId = $partnerId = elgg_get_plugin_setting('partner_id', 'archive');


?>

<script>
    var ks = "<?php echo $ks?>";
    var serviceUrl = "<?php echo $serviceUrl?>";
    var partnerId = "<?php echo $partnerId?>";
</script>

<div ng-app="mindsUploader">
    <ng-view></ng-view>
</div>
