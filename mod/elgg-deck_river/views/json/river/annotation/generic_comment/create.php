<?php
/**
 * JSON comment river view
 *
 * @uses $vars['item']
 */

global $jsonexport;

$mention = elgg_extract('mention', $vars, false);

$comment = $vars['item']->getAnnotation();

switch ($vars['item']->subtype) {
	case 'markdown_wiki':
		$vars['item']->summary = elgg_view('river/elements/markdown_wiki_comment_summary', array(
				'item' => $vars['item'],
				'hash' => '#item-annotation-' . $comment->id
		), FALSE, FALSE, 'default');
		break;
	case 'workflow_card':
		$vars['item']->summary = elgg_view('river/elements/workflow_card_comment_summary', array(
				'item' => $vars['item'],
				'hash' => '#item-annotation-' . $comment->id
		), FALSE, FALSE, 'default');
		break;
	default:
		$vars['item']->summary = elgg_view('river/elements/summary', array(
				'item' => $vars['item'],
				'hash' => '#item-annotation-' . $comment->id
		), FALSE, FALSE, 'default');
		break;
}

if ($mention) {
	$excerpt = deck_river_highlight_mention($comment->value, $mention);
} else {
	$excerpt = elgg_get_excerpt($comment->value, 140);
}

$vars['item']->message = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $excerpt);

$jsonexport['results'][] = $vars['item'];