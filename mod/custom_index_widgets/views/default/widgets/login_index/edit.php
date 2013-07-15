<?php 
	$widget_title = $vars['entity']->widget_title;
	
	$show_welcome = $vars['entity']->show_welcome;
	if (!isset($show_welcome)) $show_welcome = "yes";
	
	$guest_only = $vars['entity']->guest_only;
	if (!isset($guest_only)) $guest_only = "no";
?>
<p>
  <?php echo elgg_echo('custom_index_widgets:widget_title'); ?>:
  <?php
	echo elgg_view('input/text', array(
			'name' => 'params[widget_title]',                        
			'value' => $widget_title
		));
	?>
</p>
<p>
      <?php echo elgg_echo('custom_index_widgets:guest_only'); ?>
      :
      <?php
      echo elgg_view('input/dropdown', array('name'=>'params[guest_only]', 
      										 'options_values'=>array('yes'=>'yes', 'no'=>'no'),
       										 'value'=>$guest_only));
      ?>
</p>