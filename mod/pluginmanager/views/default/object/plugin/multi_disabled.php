<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$plugin = $vars['entity'];

$id = $plugin->getID();
$path = htmlspecialchars($plugin->getPath());
$message = elgg_echo('admin:plugins:warning:invalid', array($id));
$error = $plugin->getError();
?>
<div class="elgg-plugin elgg-multi-disabled" id="elgg-plugin-<?php echo $plugin->guid; ?>" style="display:none;"></div>