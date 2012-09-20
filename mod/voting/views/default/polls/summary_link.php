<?php
$poll = $vars['entity'];
if ($poll) {
	$responses = $poll->countAnnotations('vote');
	if ($responses == 1) {
		$noun = elgg_echo('polls:noun_response');
	} else {
		$noun = elgg_echo('polls:noun_responses');
	}
	$info .= "($responses $noun)";
	$text = "{$poll->question}";
	$link = elgg_view('output/url', array(
		'text' => $text,
		'href' => $poll->getURL()
	));
	echo $link. ' '.$info;
}
