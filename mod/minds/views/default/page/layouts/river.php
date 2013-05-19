<?php
/**
 * Layout for main column with one sidebar
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content HTML for the main column
 * @uses $vars['sidebar'] Optional content that is displayed in the sidebar
 * @uses $vars['title']   Optional title for main content area
 * @uses $vars['class']   Additional class to apply to layout
 * @uses $vars['nav']     HTML of the page nav (override) (default: breadcrumbs)
 */

$class = 'elgg-layout elgg-layout-one-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// allow page handlers to override the default filter
if (isset($vars['filter'])) {
	$vars['filter_override'] = $vars['filter'];
}
$filter = elgg_view('page/layouts/content/river_filter', $vars);

//load the inline JS because we have them in news feeds
elgg_load_js('uiVideoInline');

?>
      
<div class="<?php echo $class; ?> news">
	<?php
		if (elgg_is_logged_in() && (elgg_get_context() == 'news')) {		
	?>

	<div class="side">
		<?php
			echo elgg_view('page/elements/miniprofile', $vars);
			echo elgg_view('page/elements/friends', $vars);
			echo elgg_view('page/elements/groups', $vars);
			echo elgg_view('page/elements/sidebar', $vars);
		?>
	</div>

	<div class="elgg-body content">
		<?php
		   
		   elgg_load_js('elgg.wall');
			
			$content .= elgg_view_form('wall/add', array('name'=>'elgg-wall-news'), array('to_guid'=> elgg_get_logged_in_user_guid(), 'ref'=>'news'));

			echo elgg_view_module('wall', null, $content);
			
			echo $filter;
		    

			if (isset($vars['title'])) {
				echo elgg_view_title($vars['title']);
			}			

			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			
			if (isset($vars['content'])) {
				echo $vars['content'];
			}

		?>
	</div>
	<?php 	} else { //end of elgg_is_logged_in() condition ?>


	<div class="elgg-main elgg-body">
		<?php
			echo $nav;
			
			if (isset($vars['title'])) {
				echo elgg_view_title($vars['title']);
			}
			// @todo deprecated so remove in Elgg 2.0
			if (isset($vars['area1'])) {
				echo $vars['area1'];
			}
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
	<?php } ?>
</div>
