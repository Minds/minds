<?php
/**
 * QUOTA
 */
 
$user = elgg_get_logged_in_user_entity();
$bytes = $user->quota_storage;
$kb = $bytes /1024;
$mb = $kb /1024;
echo round($mb) . "Mb";
