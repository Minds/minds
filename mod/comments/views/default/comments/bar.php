<?php
/**
 * Load CSS and JS libraries
 */

elgg_load_css('minds_comments');
elgg_load_js('minds_comments');


$comments_view = elgg_view('comments/list', array('comments'=>$vars['comments']));
$comments_input = elgg_view('comments/input', array('parent_guid'=>$vars['parent_guid']));

?>
<div id="minds-comments-<?php echo $vars['parent_guid'] ?>" class="minds-comments-bar clearfix" data-offset="<?php echo $vars['comments'][0]->guid;?>">
    <?php if($vars['comments']){ ?>
    <div class="show-more" data-parent-guid="<?php echo $vars['parent_guid']; ?>" >
    	Load earlier
    </div>
    <?php } ?>
	<div class="comments">
	    <?php echo $comments_view ?>
	</div>

	<?php echo $vars['show_form'] ? $comments_input : NULL; ?>
</div>
