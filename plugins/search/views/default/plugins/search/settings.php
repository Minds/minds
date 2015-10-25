<?php
/**
 * elasticsearch
 *
 * @package elasticsearch
 */

echo elgg_view("input/text", array("name"=>"params[server_addr]", "value"=>elgg_get_plugin_setting('server_addr', 'search')));

