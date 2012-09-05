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
 gatekeeper();

$class = 'elgg-layout elgg-layout-one-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

?>
      
<div class="<?php echo $class; ?>">
	  	<div class="elgg-sidebar-mobile">
				<?php
					echo elgg_view('page/elements/sidebar', $vars);
				?>
			</div>

	<div class="elgg-body">
		<?php
		   
		   if(elgg_get_context() == 'news'){
		   		
		   	elgg_load_js('elgg.wall');
			
			$content .= elgg_view_form('wall/add', array('name'=>'elgg-wall-news'), array('to_guid'=> elgg_get_logged_in_user_guid()));

			echo elgg_view_module('wall', null, $content);
			
		   }
			
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

</div>
