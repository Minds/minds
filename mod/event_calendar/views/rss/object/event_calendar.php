<?php

	/**
	 * Elgg default object view
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	$title = $vars['entity']->title;
	
	$event_items = event_calendar_get_formatted_full_items($vars['entity']);
	$items = array();
	foreach($event_items as $item) {
		if (trim($item->value)) {
			$items[] = '<b>'.$item->title.'</b>: '.$item->value;
		}
	}
	
	$description = '<p>'.implode('<br />',$items).'</p>';
	
	if ($vars['entity']->long_description) {
		$description .= '<p>'.autop($vars['entity']->long_description).'</p>';
	} else {
		$description .=  '<p>'.$vars['entity']->description.'</p>';
	}

?>

	<item>
	  <guid isPermaLink='true'><?php echo htmlspecialchars($vars['entity']->getURL()); ?></guid>
	  <link><?php echo htmlspecialchars($vars['entity']->getURL()); ?></link>
	  <title><![CDATA[<?php echo $title; ?>]]></title>
	  <description><![CDATA[<?php echo $description; ?>]]></description>
	  <?php
			$owner = $vars['entity']->getOwnerEntity();
			if ($owner)
			{
?>
	  <dc:creator><?php echo $owner->name; ?></dc:creator>
<?php
			}
	  ?>
	  <?php
			if (
				($vars['entity'] instanceof Locatable) &&
				($vars['entity']->getLongitude()) &&
				($vars['entity']->getLatitude())
			) {
				?>
				<georss:point><?php echo $vars['entity']->getLatitude(); ?> <?php echo $vars['entity']->getLongitude(); ?></georss:point>
				<?php
			}
	  ?>
	  <?php echo elgg_view('extensions/item'); ?>
	</item>
