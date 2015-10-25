<?php

header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

elgg_load_css('minds.themewidgets');

$ts = time();
$token = generate_action_token($ts);

$tokens = "__elgg_token=$token&__elgg_ts=$ts";

$url = get_input('url');
 

elgg_set_ignore_access();
$entities = elgg_get_entities(array( // TODO Do this in an efficient cassandra way
    'type' => 'object',
    'subtype' => 'mind_widget_voting_stub',
    'limit' => 999999,
));
$entity = null;
if ($entities){
    foreach ($entities as $e)
        if ($e->url == $url) {
            $entity = $e;
            break;
        }
}

?>
<span id="likes_count"><?php if ($entity) echo $entity->thumbcount;  ?></span> 
<a class="entypo minds-vote-up" href="<?php echo elgg_get_site_url(); ?>action/minds_widgets/service/<?php echo $vars['tab']; ?>?<?php echo $tokens; ?>&vote=up&url=<?php echo urlencode(get_input('url')); ?>" title="Vote Up" <?php if (!elgg_is_logged_in()) { ?> onClick='window.open("<?php echo current_page_url() . '&fl=y'; ?>", "Vote Up", "width=800,height=600"); return false;' <?php } ?>><span class="mind_graf" style="display:none;">&#128077;</span><span class="mind_fall">Up</span></a> <span class="mind_fall"> / </span>
<a class="entypo minds-vote-down" href="<?php echo elgg_get_site_url(); ?>action/minds_widgets/service/<?php echo $vars['tab']; ?>?<?php echo $tokens; ?>&vote=down&url=<?php echo urlencode(get_input('url')); ?>" title="Vote Down" <?php if (!elgg_is_logged_in()) { ?> onClick='window.open(<?php echo current_page_url() . '&fl=y'; ?>, "Vote Down", "width=800,height=600"); return false;' <?php } ?>><span class="mind_graf" style="display:none;">&#128078;</span><span class="mind_fall">Down</span></a>
