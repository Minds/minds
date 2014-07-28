<?php
/**
 * Image thumbnail view
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

elgg_set_ignore_access(true);

$guid = (int) get_input('guid');
$size = get_input('size');
$image = get_entity($guid,'object');

if (!$image) {
	// @todo
//	return true;
}

if ($size == 'master' || !$image->getThumbnail($size)) {
	$contents = $image->getImage();
} else {
	$contents = $image->getThumbnail($size);
}
if(get_input('debug')){
var_dump($size, $contents); 
exit;
}
if (!$contents) {
	forward("mod/tidypics/graphics/image_error_$size");
}

// expires every 14 days
$expires = 14 * 60*60*24;

// overwrite header caused by php session code so images can be cached
$mime = $image->getMimeType();
header("Content-Type: $mime");
header("Content-Length: " . strlen($contents));
header("Cache-Control: public", true);
header("Pragma: public", true);
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT', true);

// Return the thumbnail and exit
echo $contents;

elgg_set_ignore_access(false);
exit;
