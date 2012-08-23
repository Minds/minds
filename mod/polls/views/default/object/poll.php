<?php

/**
 * Elgg poll individual post view
 * 
 * @uses $vars['entity'] Optionally, the poll post to view
 */

if (isset($vars['entity'])) {
	$full = $vars['full_view'];
	$poll = $vars['entity'];

	$owner = $poll->getOwnerEntity();
	$container = $poll->getContainerEntity();
	$categories = elgg_view('output/categories', $vars);
		
	$owner_icon = elgg_view_entity_icon($owner, 'tiny');
	$owner_link = elgg_view('output/url', array(
				'href' => "polls/owner/$owner->username",
				'text' => $owner->name,
				'is_trusted' => true,
	));
	$author_text = elgg_echo('byline', array($owner_link));
	$tags = elgg_view('output/tags', array('tags' => $poll->tags));
	$date = elgg_view_friendly_time($poll->time_created);

	// TODO: support comments off
	// The "on" status changes for comments, so best to check for !Off
	if ($poll->comments_on != 'Off') {
		$comments_count = $poll->countComments();
		//only display if there are commments
		if ($comments_count != 0) {
			$text = elgg_echo("comments") . " ($comments_count)";
			$comments_link = elgg_view('output/url', array(
						'href' => $poll->getURL() . '#poll-comments',
						'text' => $text,
						'is_trusted' => true,
			));
		} else {
			$comments_link = '';
		}
	} else {
		$comments_link = '';
	}

	// do not show the metadata and controls in widget view
	if (elgg_in_context('widgets')) {
		$metadata = '';
	} else {
		$metadata = elgg_view_menu('entity', array(
					'entity' => $poll,
					'handler' => 'polls',
					'sort_by' => 'priority',
					'class' => 'elgg-menu-hz',
		));
	}
		
	$subtitle = "$author_text $date $comments_link $categories";
	if ($full) {

		$params = array(
			'entity' => $poll,
			'title' => false,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags,
		);
		$params = $params + $vars;
		$summary = elgg_view('object/elements/summary', $params);

		echo elgg_view('object/elements/full', array(
			'summary' => $summary,
			'icon' => $owner_icon,
		));
		
		echo elgg_view('polls/body',$vars);

	} else {
		// brief view
	
		$params = array(
			'entity' => $poll,
			'metadata' => $metadata,
			'subtitle' => $subtitle,
			'tags' => $tags
		);
		$params = $params + $vars;
		$list_body = elgg_view('object/elements/summary', $params);
	
		echo elgg_view_image_block($owner_icon, $list_body);
	}
}
