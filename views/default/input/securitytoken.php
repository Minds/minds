<?php
/**
 * CSRF security token view for use with secure forms.
 *
 * It is still recommended that you use input/form.
 *
 * @package Elgg
 * @subpackage Core
 */

$ts = time();
$uri = substr($vars['action'], strlen(elgg_get_site_url())-1);
$token = minds\core\token::generate($uri, $ts);

echo elgg_view('input/hidden', array('name' => '__elgg_token', 'value' => $token));
echo elgg_view('input/hidden', array('name' => '__elgg_ts', 'value' => $ts));
