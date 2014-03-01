<?php
/**
 * Return youtube embed code
 */

$url = get_input('url');
$size = get_input('size', 'adaptive');

if($size == 'small'){
	$w = 320; 
	$h = 180;
} elseif($size == 'medium'){
	$w = 436;
	$h = 245;
} elseif($size == 'large'){
	$w = 730;
	$h = 411;
} elseif($size == 'adaptive'){
	$w = '100%';
	$h = 420;
} else {
	return false;
}

$u = parse_url($url);
$q = htmlspecialchars_decode($u['query']);
parse_str($q);

$embed = '<iframe width="'.$w.'" height="'.$h.'" src="http://youtube.com/embed/'.$v.'" frameborder="0"></iframe>';
$icon = '<img src="http://img.youtube.com/vi/'.$v.'/hqdefault.jpg" width="0" height="0"/>';
echo $embed . $icon;
