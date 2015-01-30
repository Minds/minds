<?php
/**
 * Edit / add a page
 */

extract($vars);
$desc_class = $use_view ? 'class="hidden"' : '';
$view_info_class = $use_view ? '' : 'class="hidden"';
$use_view_checked = $use_view ? 'checked="checked"' : '';
$visible_check = $visible_through_walled_garden ? 'checked="checked"' : '';
$requires_login_check = $requires_login ? 'checked="checked"' : '';

?>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'title',
		'value' => $title
	));
	?>
</div>

<div>
	<label><?php echo elgg_echo('anypage:path'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'page_path',
		'value' => $page_path,
		'id' => 'anypage-path'
	));
	// add warning if there is an unsupported character
	if ($entity && $entity->hasUnsupportedPageHandlerCharacter($entity->getPagePath())) {
		$module_title = elgg_echo('anypage:warning');
		$msg = elgg_echo('anypage:unsupported_page_handler_character');
		echo elgg_view_module('info', $module_title, $msg, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));
	}

	// add warning if there is a page handler conflict
	if ($entity && AnyPage::hasPageHandlerConflict($entity->getPagePath())) {
		$module_title = elgg_echo('anypage:warning');
		$msg = elgg_echo('anypage:page_handler_conflict');
		echo elgg_view_module('info', $module_title, $msg, array('class' => 'anypage-message pvm elgg-message elgg-state-error'));
	}

	echo elgg_echo('anypage:path_full_link') . ': ';
	echo elgg_view('output/url', array(
		'href' => $entity ? $entity->getPagePath() : '',
		'text' => elgg_normalize_url($entity ? $entity->getPagePath() : ''),
		'class' => 'anypage-updates-on-path-change'
	));
	?>
</div>

<div>
	<label>
<?php if (elgg_get_config('walled_garden')) { ?>
		<?php
			echo elgg_echo('anypage:visible_through_walled_garden');
		?>
		<input type="checkbox" name="visible_through_walled_garden" value="1" <?php echo $visible_check; ?> />
<?php } else { ?>
		<?php
			echo elgg_echo('anypage:requires_login');
		?>
		<input type="checkbox" name="requires_login" value="1" <?php echo $requires_login_check; ?> />
<?php } ?>
	</label>
</div>

<div>
	<label>
	<?php
		echo elgg_echo('anypage:use_view');
	?>
	<input type="checkbox" id="anypage-use-view" name="use_view" value="1" <?php echo $use_view_checked; ?> />
	</label>
</div>

<div id="anypage-view-info" <?php echo $view_info_class;?>>
	<p>
	<?php
	echo '<p>' . elgg_echo('anypage:view_info');
	echo " anypage<span class=\"anypage-updates-on-path-change\">$page_path</span>";
	echo '</p>';
	?>
	</p>
</div>

<div id="anypage-description" <?php echo $desc_class;?>>
	<label><?php echo elgg_echo('anypage:body'); ?></label>
	<?php
	echo elgg_view('input/longtext', array(
		'name' => 'description',
		'value' => $description
	));
	?>
</div>
<div class="elgg-foot">
<?php

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
	echo elgg_view('output/confirmlink', array(
		'class' => 'float-alt elgg-button elgg-button-action',
		'text' => elgg_echo('delete'),
		'href' => 'action/anypage/delete?guid=' . $guid
	));
}

echo elgg_view('input/submit', array('value' => elgg_echo("save")));

?>
</div>