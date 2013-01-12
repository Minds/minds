<?php

if (elgg_get_plugin_setting('forum_comments', 'hypeAlive') !== off) {
    echo elgg_view('hj/group_posts/bar', array(
        'entity' => $vars['entity']
    ));
} else {
    $show_add_form = elgg_extract('show_add_form', $vars, true);

    echo '<div id="group-replies" class="mtl">';

    $options = array(
        'guid' => $vars['entity']->getGUID(),
        'annotation_name' => 'group_topic_post',
    );
    $html = elgg_list_annotations($options);
    if ($html) {
        echo '<h3>' . elgg_echo('group:replies') . '</h3>';
        echo $html;
    }

    if ($show_add_form) {
        $form_vars = array('class' => 'mtm');
        echo elgg_view_form('discussion/reply/save', $form_vars, $vars);
    }

    echo '</div>';
}
