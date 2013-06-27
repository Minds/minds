<?php 
	$widget_title = $vars['entity']->widget_title;
	$widget_video_width = $vars['entity']->widget_video_width;
	$widget_video_height = $vars['entity']->widget_video_height;
	$widget_video_url = $vars['entity']->widget_video_url;
	$widget_video_title = $vars['entity']->widget_video_title;
	
	$guest_only = $vars['entity']->guest_only;
	if (!isset($guest_only)) $guest_only = "no";
	
	$box_style = $vars['entity']->box_style;
	if (!isset($box_style)) $box_style = "collapsable";
	
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
<?php echo elgg_echo('custom_index_widgets:widget_video_caption'); ?>	
<?php
	echo elgg_view('input/text', array(
			'name' => 'params[widget_video_caption]',                        
			'value' => $widget_video_caption
		));
	?>
</p>
<p>
<?php echo elgg_echo('custom_index_widgets:widget_video_url'); ?>	
<?php
	echo elgg_view('input/text', array(
			'name' => 'params[widget_video_url]',                        
			'value' => $widget_video_url
		));
	?>
</p>
<p>
<?php echo elgg_echo('custom_index_widgets:widget_video_width'); ?>	
<?php
	echo elgg_view('input/text', array(
			'name' => 'params[widget_video_width]',                        
			'value' => $widget_video_width
		));
	?>
</p>
<p>
<?php echo elgg_echo('custom_index_widgets:widget_video_height'); ?>	
<?php
	echo elgg_view('input/text', array(
			'name' => 'params[widget_video_height]',                        
			'value' => $widget_video_height
		));
	?>
</p>
<p>
      <?php echo elgg_echo('custom_index_widgets:box_style'); ?>
      :
      <?php
      echo elgg_view('input/dropdown', array('name'=>'params[box_style]', 
      										 'options_values'=>array('plain'=>'Plain', 'plain collapsable'=>'Plain and collapsable', 'collapsable'=>'Collapsable', 'standard' => 'No Collapsable'),
       										 'value'=>$box_style));
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

