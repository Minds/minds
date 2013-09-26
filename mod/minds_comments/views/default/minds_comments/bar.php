<?php
/**
 * Load CSS and JS libraries
 */

elgg_load_css('minds_comments');
elgg_load_js('minds_comments');

elgg_load_js('hj.framework.ajax');

$type = elgg_extract('type', $vars, false);
$pid = elgg_extract('pid', $vars, false);

$comments_view = minds_comments_view_list($type, $pid);

$comments_input = elgg_view('minds_comments/input', array('type'=>$type, 'pid'=>$pid));

//if(elgg_get_context() == 'news'){
?>
<div id="minds-comments-<?php echo $pid ?>" class="minds-comments-bar clearfix">
    <div class="minds-comments-menu">
	<?php echo $menu; ?>
    </div>
    <ul class="hj-annotations-list hj-syncable">
    
	<div class="comments">
	    <?php echo $comments_view ?>
	</div>
    <div class="hj-comments-bubble hj-comments-input hidden"><?php echo $comments_input ?></div>
    </ul>

</div>
