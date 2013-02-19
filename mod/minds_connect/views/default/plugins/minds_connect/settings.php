<?php

$url_parts = parse_url(elgg_get_site_url());

$minds_url = $vars['entity']->minds_url ? $vars['entity']->minds_url : 'https://www.minds.com';

$checked = false;

if ($vars['entity']->ssl_callback == 'yes') {
    $checked = true;
}

?>


<div>
    <label><?php echo elgg_echo('minds_connect:client_id'); ?>:</label><br />
    <input type="text" name="params[client_id]" value="<?php echo $vars['entity']->client_id; ?>" class="elgg-input-text" style="width: 300px;">
</div>

<div>
    <label><?php echo elgg_echo('minds_connect:client_secret'); ?>:</label><br />
    <input type="password" name="params[client_secret]" value="<?php echo $vars['entity']->client_secret; ?>" class="elgg-input-text" style="width: 300px;">
</div>

<div>
    <label><?php echo elgg_echo('minds_connect:callback'); ?>:</label> <?php echo $url_parts['host'] . '/minds_connect/authorized'; ?>

    <?php if ($url_parts['scheme'] == 'http'): ?>

        <div style="margin:10px 0 10px 0;">
            Your site is not running SSL. It is recommended although not required that the callback URL use SSL.<br /> 
            If your site supports SSL check below to forcing using SSL for the callback URL.
        </div>

        <label>SSL Callback: </label>
        <?php echo elgg_view("input/checkbox", array('name' => 'params[ssl_callback]', 'value' => 'yes', 'default' => 'no', 'checked' => $checked)); ?>

    <?php endif; ?>
</div>

<div>
    <label><?php echo elgg_echo('minds_connect:url'); ?>:</label><br />
    <input type="text" name="params[minds_url]" value="<?php echo $minds_url; ?>" class="elgg-input-text" style="width: 300px;">
</div>

