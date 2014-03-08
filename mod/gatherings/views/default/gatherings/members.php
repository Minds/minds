<?php
/**
 * Comma separated and popup module list of chat members.
 *
 * Views a comma separated list viewing "+5 more" link
 * which opens a list of all members in a popup module.
 *  
 * @uses $vars['entity']
 */

$chat = elgg_extract('entity', $vars);
 
// Get chat members
$members = $chat->getMemberEntities();

$count = 0;
$member_names = array();
$more_members = array();
foreach ($members as $member) {
	if ($count < 3) {
		$member_names[] = $member->name;
	} else {
		$more_members[] = $member->name;
	}
	$count++;
}

$num_more_members = count($more_members);

if ($num_more_members) {
	$guid = $chat->getGUID();

	$more_link = elgg_view('output/url', array(
		'text' => elgg_echo('chat:members:more', array($num_more_members)),
		'url' => '',
		'rel' => 'popup',
		'href' => "#members-$guid",
	));
	$member_names[] = $more_link;
	
	$list = "<div class='elgg-module elgg-module-popup elgg-chat-members hidden clearfix' id='members-$guid'>";
	$list .= elgg_view_entity_list($members);
	$list .= "</div>";
	echo $list;
}

$member_list = implode(', ', $member_names);

echo $member_list;