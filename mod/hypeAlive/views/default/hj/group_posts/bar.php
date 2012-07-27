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

if (!$entity || !elgg_instanceof($entity, 'object', 'groupforumtopic')) {
    return true;
}

$params['container_guid'] = $entity->guid;
$params['aname'] = 'group_topic_post';

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
<div id="hj-annotations-<?php echo $entity->guid ?>" class="hj-annotations-bar clearfix">
    <div class="hj-annotations-menu">
        <?php echo $menu ?>
    </div>
    <ul class="hj-annotations-list hj-syncable">
        <div class="hj-comments-bubble hj-comments-input hidden"><?php echo $comments_input ?></div>
        <div class="annotations">
            <?php echo $comments_view ?>
        </div>
        <div class="likes">
            <?php echo $likes_view ?>
        </div>
    </ul>

</div>
