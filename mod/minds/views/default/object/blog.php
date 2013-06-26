<?php
/**
 * View for blog objects
 *
 * @package Blog
 */

$full = elgg_extract('full_view', $vars, FALSE);
$sidebar = elgg_extract('sidebar', $vars, FALSE);
$blog = elgg_extract('entity', $vars, FALSE);

if (!$blog) {
	return TRUE;
}

$owner = $blog->getOwnerEntity();
$container = $blog->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = strip_tags($blog->excerpt);
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($blog->description);
}

$owner_icon = elgg_view_entity_icon($owner, 'small');
$owner_link = elgg_view('output/url', array(
	'href' => "blog/owner/$owner->username",
	'text' => $owner->name,
	'is_trusted' => true,
));
$author_text = elgg_echo('byline', array($owner_link));
$date = elgg_view_friendly_time($blog->time_created);

// The "on" status changes for comments, so best to check for !Off
if ($blog->comments_on != 'Off') {
	$comments_count = $blog->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $blog->getURL() . '#blog-comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'blog',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
	'full_view' => $full
));

$subtitle = "$author_text $date $comments_link $categories";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full) {

	$body = elgg_view('output/longtext', array(
		'value' => $blog->description,
		'class' => 'blog-post',
	));
	
	$body .= elgg_view('minds/license', array('license'=>$blog->license));
	
	//if blog is public, show social links
	if($blog->access_id == 2){
		$body .= elgg_view('minds_social/social_footer');
	}
	
	$params = array(
		'entity' => $blog,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		//'summary' => $summary,
		//'icon' => $owner_icon,
		'body' => $body,
	));

} elseif($sidebar) {
	
	$image = elgg_view('output/img', array('src'=>minds_fetch_image($blog->description, $blog->owner_guid), 'class'=>'rich-image'));
	$img_link = '<div class="rich-image-container">' . elgg_view('output/url', array('href'=>$blog->getURL(), 'text'=>$image)) . '</div>';
	$title = elgg_view('output/url', array('href'=>$blog->getURL(), 'text'=> '<h3>'.$blog->title.'</h3>', 'class'=>'title'));
	//echo elgg_view_image_block($img_link, $title, array('class'=>'rich-content sidebar'));
	echo $img_link;
	echo $title;
} else {
	// brief view

	$image = elgg_view('output/img', array('src'=>minds_fetch_image($blog->description, $blog->owner_guid), 'class'=>'rich-image'));
	$title = elgg_view('output/url', array('href'=>$blog->getURL(), 'text'=>elgg_view_title($blog->title)));
	$extras = '<p class="excerpt">' . elgg_view('output/url', array('href'=>$blog->getURL(), 'text'=>$excerpt)) . '</p>';

	$owner_link  = elgg_view('output/url', array('href'=>$owner->getURL(), 'text'=>$owner->name));

        $subtitle = '<i>'.
                elgg_echo('by') . ' ' . $owner_link . ' ' .
                elgg_view_friendly_time($blog->time_created) . '</i>';

        $header = elgg_view_image_block(elgg_view_entity_icon($owner, 'small'), $title . $subtitle);
	echo $metadata;
        echo $header;
        echo elgg_view('output/url', array('href'=>$blog->getURL(), 'text'=>$image, 'class'=>'blog-rich-image-holder'));
	echo $extras;
}
