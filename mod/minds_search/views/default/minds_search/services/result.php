<?php
/**
 * Minds Search Service Result
 *
 * @package minds_search
 */

$result = $vars['result'];

if ($result['_type'] == 'photo')
	echo elgg_view('minds_search/services/types/image', array('photo' => $result['_source'], 'full_view'=>true));
if ($result['_type'] == 'video')
	echo elgg_view('minds_search/services/types/video', array('video' => $result['_source'], 'full_view'=>true));
if ($result['_type'] == 'sound')
	echo elgg_view('minds_search/services/types/sound', array('sound' => $result['_source'], 'full_view'=>true));
