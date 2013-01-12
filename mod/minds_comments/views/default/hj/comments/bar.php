<?php
/**
 * Load CSS and JS libraries
 */
elgg_load_css('minds_comments');
if (elgg_is_logged_in()) {
    elgg_load_js('minds_comments');
}
elgg_load_js('hj.framework.ajax');

if (!elgg_is_logged_in()) {
    return true;
}

$entity = elgg_extract('entity', $vars, false);
$item = elgg_extract('item', $vars, false);

if (!$entity) {
    return true;
}
if ($item->action_type != 'create' && $item) {
    $river_id = $item->id;
    $selector_id = $river_id;
} else {
    $guid = $entity->guid;
    $selector_id = $guid;
}
$params['parent_guid'] = $guid;
$params['river_id'] = $river_id;
$params['aname'] = elgg_extract('aname', $vars, 'generic_comment');

$comments_view = hj_alive_view_comments_list($entity, $params);

$menu = elgg_view_menu('comments', array(
    'entity' => $entity,
    'handler' => $handler,
    'class' => 'elgg-menu-hz',
    'sort_by' => 'priority',
    'params' => $params
	));

$params['entity'] = $entity;
$comments_input = elgg_view('hj/comments/input', $params);

unset($params['aname']);
unset($params['entity']);
//if(elgg_get_context() == 'news'){
?>
<div id="hj-annotations-<?php echo $selector_id ?>" class="hj-annotations-bar clearfix">
    <div class="hj-annotations-menu">
	<?php echo $entity->getSubtype() == 'hjannotation' ? $menu : '' ?>
    </div>
    <ul class="hj-annotations-list hj-syncable">
    
	<div class="annotations">
	    <?php echo $comments_view ?>
	</div>
    <div class="hj-comments-bubble hj-comments-input hidden"><?php echo $comments_input ?></div>
    </ul>

</div>