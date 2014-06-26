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

$class = 'layout layout-one-sidebar elgg-layout elgg-layout-one-sidebar clearfix';
if (isset($vars['class'])) {
	$class = "$class {$vars['class']}";
}

// navigation defaults to breadcrumbs
$nav = elgg_extract('nav', $vars, elgg_view('navigation/breadcrumbs'));

if($vars['header']){
?>

<div class="minds-body-header">
	<div class="inner">
		<?php
			echo $vars['header'];
			//echo $nav;
//			echo $vars['header'];	
	?>
		
	</div>	
</div>
<?php } //END IF HEADER ?>
	
<div class="inner elgg-inner fixed">
	<div class="<?php echo $class; ?>">
		<div class="sidebar elgg-sidebar">
        	<?php
                	echo elgg_view('page/elements/sidebar', $vars);
        	?>
		</div>		
		<div class="main body elgg-main elgg-body">
			<?php
		
				// @todo deprecated so remove in Elgg 2.0
				if (isset($vars['area1'])) {
					echo $vars['area1'];
				}
				if (isset($vars['content'])) {
					echo $vars['content'];
				}
			?>
		</div>
		<div class="footer elgg-footer">
			<?php 
				if(isset($vars['footer'])){
					echo $vars['footer'];
				}
			?>
		</div>
	</div>
</div>
