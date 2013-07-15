<?php
    $num_items = $vars['entity']->num_items;
    if (!isset($num_items)) $num_items = 10;
   
    $widget_title = $vars['entity']->widget_title;
	
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
<?php echo elgg_echo('custom_index_widgets:num_items'); ?>
   
<?php
    echo elgg_view('input/dropdown', array(
            'name' => 'params[num_items]',
            'options_values' => array( '1' => '1',
                                       '3' => '3',
                                        '5' => '5',
                                       '8' => '8',
                                       '10' => '10',
                                       '12' => '12',
                                       '15' => '15',
                                       '20' => '20',
                                       '30' => '30',
                                       '40' => '40',
                                       '50' => '50',
                                       '100' => '100',
                                     ),
            'value' => $num_items
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