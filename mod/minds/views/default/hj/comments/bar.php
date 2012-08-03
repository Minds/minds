<?php
/**
 * Load CSS and JS libraries
 */
elgg_load_css('hj.comments.base');
if (elgg_is_logged_in()) {
    elgg_load_js('hj.comments.base');
    elgg_load_js('hj.likes.base');
}
elgg_load_js('hj.framework.ajax');

if (!elgg_is_logged_in()) {
    return true;
}

$entity = elgg_extract('entity', $vars, false);

if (!$entity) {
    return true;
}

if ($entity->getType() == 'river') {
    $river_id = $entity->id;
    $selector_id = $river_id;
} else {
    $guid = $entity->guid;
    $selector_id = $guid;
}

$params['container_guid'] = $guid;
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
$likes_view = hj_alive_view_likes_list($params);
?>
<div id="hj-annotations-<?php echo $selector_id ?>" class="hj-annotations-bar clearfix">
    <div class="hj-annotations-menu">
	<?php echo $guid ? $menu : '' ?>
    </div>
    <ul class="hj-annotations-list hj-syncable">
	<div class="hj-comments-bubble hj-comments-input <?php if($guid){?>hidden<?php }?>"><?php echo $comments_input ?></div>
	<div class="annotations">
	    <?php echo $comments_view ?>
	</div>
	<div class="likes">
	    <?php echo $likes_view ?>
        </div>
    </ul>

</div>
