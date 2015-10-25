<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

//the page owner
$owner = get_user($vars['entity']->owner_guid);

//the number of files to display
$num = (int) $vars['entity']->num_display;
if (!$num) {
	$num = 4;
}		
		
$posts = elgg_get_entities(array('type'=>'object','subtype'=>'market', 'owner_guid' => $owner->guid, 'limit'=>$num));

echo '<ul class="elgg-list">';		
// display the posts, if there are any
if (is_array($posts) && sizeof($posts) > 0) {

	if (!$size || $size == 1){
		foreach($posts as $post) {
			echo "<li class=\"pvs\">";
			$category = "<b>" . elgg_echo('market:category') . ":</b> " . elgg_echo($post->marketcategory);
			$comments_count = $post->countComments();
			$text = elgg_echo("comments") . " ($comments_count)";
			$comments_link = elgg_view('output/url', array(
						'href' => $post->getURL() . '#market-comments',
						'text' => $text,
						));
			$market_img = elgg_view('output/url', array(
						'href' => "market/view/{$post->guid}/" . elgg_get_friendly_title($post->title),
						'text' => elgg_view('market/thumbnail', array('marketguid' => $post->guid, 'size' => 'small')),
						));

			$subtitle = "{$category}<br><b>" . elgg_echo('market:price') . ":</b> {$post->price}";
			$subtitle .= "<br>{$author_text} {$date} {$comments_link}";
			$params = array(
				'entity' => $post,
				'metadata' => $metadata,
				'subtitle' => $subtitle,
				'tags' => $tags,
				'content' => $excerpt,
			);
			$params = $params + $vars;
			$list_body = elgg_view('object/elements/summary', $params);
			echo elgg_view_image_block($market_img, $list_body);
			echo "</li>";
		}
			
	}
	echo "</ul>";
	echo "<div class=\"contentWrapper\"><a href=\"" . $CONFIG->wwwroot . "pg/market/" . $owner->username . "\">" . elgg_echo("market:widget:viewall") . "</a></div>";

}

