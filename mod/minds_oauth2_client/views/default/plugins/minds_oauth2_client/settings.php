
<div>
    <label><?php echo elgg_echo('minds_oauth2_client:client_id'); ?>:</label><br />
    <input type="text" name="params[client_id]" value="<?php echo $vars['entity']->client_id; ?>" class="elgg-input-text" style="width: 300px;">
</div>

<div>
    <label><?php echo elgg_echo('minds_oauth2_client:client_secret'); ?>:</label><br />
    <input type="password" name="params[client_secret]" value="<?php echo $vars['entity']->client_secret; ?>" class="elgg-input-text" style="width: 300px;">
</div>

<div>
    <label><?php echo elgg_echo('minds_oauth2_client:callback'); ?>:</label> <?php echo elgg_get_site_url() . 'minds_oauth2_client/authorize'; ?>
</div>


