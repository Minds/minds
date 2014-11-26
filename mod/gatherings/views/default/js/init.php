<?php 
$public = elgg_get_plugin_user_setting('publickey', elgg_get_logged_in_user_guid(), 'gatherings');
?>

elgg.user_publicKEY = <?= json_encode($public) ?>;
