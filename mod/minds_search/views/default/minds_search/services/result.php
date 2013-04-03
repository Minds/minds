<?php
/**
 * Minds Search Service Result
 *
 * @package minds_search
 */

$result = $vars['result'];
$item_id = $result['_source']['id'];
$menu = elgg_view_menu('search_result', array(	'item_id'=>$item_id, 
												'source_href'=>$result['_source']['href'], 
												'source'=>$result['_source']['source'],
												));

$title = elgg_view_title($result['_source']['title']);
$license = elgg_view('minds/license', array('license'=>$result['_source']['license']));
$share = elgg_view('minds_social/social_footer');

echo <<<HTML
<div class="elgg-head clearfix">
	$menu $title 
</div>
<div class="search-result-license">
	$license
</div> 
<div class="search-result-social">
	$share
</div>
HTML;
if ($result['_type'] == 'photo')
	echo elgg_view('minds_search/services/types/image', array('photo' => $result['_source'], 'full_view'=>true));
if ($result['_type'] == 'video')
	echo elgg_view('minds_search/services/types/video', array('video' => $result['_source'], 'full_view'=>true));
if ($result['_type'] == 'sound')
	echo elgg_view('minds_search/services/types/sound', array('sound' => $result['_source'], 'full_view'=>true));
if ($result['_type'] == 'article')
	echo elgg_view('minds_search/services/types/article', array('article' => $result['_source'], 'full_view'=>true));
