<?php


?>

<?php 
if(\minds\core\minds::detectMultisite()){
?>
<div>
    <label>Your Minds.com username (for referrals):</label><br />
    <input type="password" name="params[owner_username]" value="<?php echo elgg_get_plugin_setting('owner_username', 'minds_nodes') ?>" class="elgg-input-text" style="width: 300px;">
</div>

<?php
return true;
}

?>
<div>
    <label>Multisite Manager Address:</label><br />
    <input type="text" name="params[manager_addr]" value="<?php echo elgg_get_plugin_setting('manager_addr', 'minds_nodes') ?>" class="elgg-input-text" style="width: 300px;">
</div>

<div>
    <label>Multisite Manager Key (token):</label><br />
    <input type="password" name="params[manager_key]" value="<?php echo elgg_get_plugin_setting('manager_key', 'minds_nodes') ?>" class="elgg-input-text" style="width: 300px;">
</div>

