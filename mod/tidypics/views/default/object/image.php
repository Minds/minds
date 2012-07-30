<?php
/**
 * Image view
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */


$full_view = elgg_extract('full_view', $vars, false);

if ($full_view) {
	echo elgg_view('object/image/full', $vars);
} else {
	echo elgg_view('object/image/summary', $vars);
}

return true;

global $CONFIG;
include_once dirname(dirname(dirname(dirname(__FILE__)))) . "/lib/exif.php";

$image = $vars['entity'];
$image_guid = $image->getGUID();
$tags = $image->tags;
$title = $image->getTitle();
$desc = $image->description;
$owner = $image->getOwnerEntity();
$friendlytime = friendly_time($image->time_created);


/********************************************************************
 *
 * search view of an image
 *
 ********************************************************************/
if (get_context() == "search") {

	// gallery view is a matrix view showing just the image - size: small
	if (get_input('search_viewtype') == "gallery") {
?>
<div class="tidypics_album_images">
	<a href="<?php echo $image->getURL();?>"><img src="<?php echo $vars['url'];?>mod/tidypics/thumbnail.php?file_guid=<?php echo $image_guid;?>&size=small" alt="thumbnail"/></a>
</div>
<?php
	} else {
		// list view displays a thumbnail icon of the image, its title, and the number of comments
		$info = '<p><a href="' .$image->getURL(). '">'.$title.'</a></p>';
		$info .= "<p class=\"owner_timestamp\"><a href=\"{$vars['url']}pg/profile/{$owner->username}\">{$owner->name}</a> {$friendlytime}";
		$numcomments = elgg_count_comments($image);
		if ($numcomments) {
			$info .= ", <a href=\"{$image->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a>";
		}
		$info .= "</p>";
		$icon = "<a href=\"{$image->getURL()}\">" . '<img src="' . $vars['url'] . 'mod/tidypics/thumbnail.php?file_guid=' . $image_guid . '&size=thumb" alt="' . $title . '" /></a>';

		echo elgg_view_listing($icon, $info);
	}

/***************************************************************
 *
 * front page view 
 *
 ****************************************************************/
} else if (get_context() == "front" || get_context() == "widget") {
	// the front page view is a clickable thumbnail of the image
?>
<a href="<?php echo $image->getURL(); ?>">
	<img src="<?php echo $vars['url'];?>mod/tidypics/thumbnail.php?file_guid=<?php echo $image_guid;?>&amp;size=thumb" class="tidypics_album_cover" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" />
</a>
	<?php
} else {

/********************************************************************
 *
 *  listing of photos in an album
 *
 *********************************************************************/
	if (!$vars['full']) {

		// plugins can override the image link to add lightbox code here
		$image_html = false;
		$image_html = trigger_plugin_hook('tp_thumbnail_link', 'album', array('image' => $image), $image_html);

		if ($image_html) {
			echo $image_html;
		} else {
			// default link to image if no one overrides
?>
<div class="tidypics_album_images">
	<a href="<?php echo $image->getURL();?>"><img src="<?php echo $vars['url'];?>pg/photos/thumbnail/<?php echo $image_guid;?>/small/" alt="<?php echo $image->title; ?>"/></a>
</div>
<?php
		}
	} else {

/********************************************************************
 *
 *  tidypics individual image display
 *
 *********************************************************************/


		$viewer = get_loggedin_user();


		// Build back and next links
		$back = '';
		$next = '';
		$album = get_entity($image->container_guid);
		$back_guid = $album->getPreviousImageGuid($image->guid);
		$next_guid = $album->getNextImageGuid($image->guid);

		if ($back_guid != 0) {
			$text = elgg_echo('image:back');
			$back = "<a href=\"{$vars['url']}pg/photos/view/$back_guid\">&laquo; $text</a>";
		}

		if ($next_guid != 0) {
			$text = elgg_echo('image:next');
			$next = "<a href=\"{$vars['url']}pg/photos/view/$next_guid\">$text &raquo;</a>";
		}

?>
<div class="contentWrapper">
	<div id="tidypics_wrapper">

		<div id="tidypics_breadcrumbs">
					<?php echo elgg_view('tidypics/breadcrumbs', array('album' => $album,) ); ?> <br />
<?php
					if (get_plugin_setting('view_count', 'tidypics') != "disabled") {

						$image->addView($viewer->guid);
						$views = $image->getViews($viewer->guid);
						if (is_array($views)) {
							echo sprintf(elgg_echo("tidypics:views"), $views['total']);
							if ($owner->guid == $viewer->guid) {
								echo ' ' . sprintf(elgg_echo("tidypics:viewsbyowner"), $views['unique']);
							} else {
								if ($views['mine']) {
									echo ' ' . sprintf(elgg_echo("tidypics:viewsbyothers"), $views['mine']);
								}
							}
						}
					}
?>
		</div>

		<div id="tidypics_desc">
					<?php echo autop($desc); ?>
		</div>
		<div id="tidypics_image_nav">
			<ul>
				<li><?php echo $back; ?></li>
				<li><?php echo $next; ?></li>
			</ul>
		</div>
		<div id="tidypics_image_wrapper">
					<?php
					// this code controls whether the photo is a hyperlink or not and what it links to
					if (get_plugin_setting('download_link', 'tidypics') != "disabled") {
						// admin allows downloads so default to inline download link
						$image_html = "<a href=\"{$vars['url']}pg/photos/download/{$image_guid}/inline/\" title=\"{$title}\" >";
						$image_html .= "<img id=\"tidypics_image\"  src=\"{$vars['url']}pg/photos/thumbnail/{$image_guid}/large/\" alt=\"{$title}\" />";
						$image_html .= "</a>";
					} else {
						$image_html = "<img id=\"tidypics_image\"  src=\"{$vars['url']}pg/photos/thumbnail/{$image_guid}/large/\" alt=\"{$title}\" />";
					}
					// does any plugin want to override the link
					$image_html = trigger_plugin_hook('tp_thumbnail_link', 'image', array('image' => $image), $image_html);
					echo $image_html;
					?>
			<div class="clearfloat"></div>
		</div>
				<?php
				// image menu (start tagging, download, etc.)

				echo '<div id="tidypics_controls"><ul>';
				echo elgg_view('tidypics/image_menu', array(
					'image_guid' => $image_guid,
					'viewer' => $viewer,
					'owner' => $owner,
					'anytags' => $image->isPhotoTagged(),
					'album' => $album, ) );
				echo '</ul></div>';

				// tagging code - photo tags on images, photo tag listing and hidden divs used in tagging
				if (get_plugin_setting('tagging', 'tidypics') != "disabled") {
					echo elgg_view('tidypics/tagging', array(
						'image' => $image,
						'viewer' => $viewer,
						'owner' => $owner, ) );
				}


				if (get_plugin_setting('exif', 'tidypics') == "enabled") {
					echo elgg_view('tidypics/exif', array('guid'=> $image_guid));
				}
?>
		<div class="tidypics_info">
<?php 
				if (!is_null($tags)) {
?>
			<div class="object_tag_string"><?php echo elgg_view('output/tags',array('value' => $tags));?></div>
<?php
				}
				if (get_plugin_setting('photo_ratings', 'tidypics') == "enabled") {
?>
			<div id="rate_container">
							<?php echo elgg_view('rate/rate', array('entity'=> $vars['entity'])); ?>
			</div>
<?php
				}

				echo elgg_echo('image:by');?> <b><a href="<?php echo $vars['url']; ?>pg/profile/<?php echo $owner->username; ?>"><?php echo $owner->name; ?></a></b>  <?php echo $friendlytime;
?>
		</div>
	</div> <!-- tidypics wrapper-->
<?php

		echo elgg_view_comments($image);

		echo '<div class="clearfloat"></div>';

		echo '</div>';  // content wrapper

	} // end of individual image display

}
