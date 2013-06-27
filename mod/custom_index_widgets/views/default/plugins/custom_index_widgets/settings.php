<?php
	 
?>
<div>
  <div class="contentWrapper">
    <table>
      <tr>
  	     <td style="width:50%"><?php echo elgg_echo("custom_index_widgets:layout"); ?></td>
  	     <td>
  	         <?php	echo elgg_view('input/dropdown', array(
          			'name' => 'params[ciw_layout]',
          			'options_values' => array(
						'index' => 'Default',
          				'index_2rmsb' => elgg_echo('custom_index_widgets:index_2rmsb'),
          				'index_2rsmb' => elgg_echo('custom_index_widgets:index_2rsmb'),
          				'index_2rhhb' => elgg_echo('custom_index_widgets:index_2rhhb'),
          				'index_2rbhh' => elgg_echo('custom_index_widgets:index_2rbhh'),
          				
          				'index_2rbsm' => elgg_echo('custom_index_widgets:index_2rbsm'),
          				'index_2rbms' => elgg_echo('custom_index_widgets:index_2rbms'),
          				
          				'index_1rsss' => elgg_echo('custom_index_widgets:index_1rsss')

          			),
          			'value' => $vars["entity"]->ciw_layout
          		));
          	?>
         </td>
      </tr>
	  <tr>
	  	<td style="width:50%"><?php echo elgg_echo("custom_index_widgets:login_style"); ?></td>
  	     <td>
  	         <?php	echo elgg_view('input/dropdown', array(
          			'name' => 'params[login_style]',
          			'options_values' => array(
          				'inlayout' => elgg_echo('custom_index_widgets:inlayout'),
          				'topbar' => elgg_echo('custom_index_widgets:topbar'),
        			),
          			'value' => $vars["entity"]->login_style
          		));
          	?>
         </td>
	  </tr>
	  <tr>
	  	<td style="width:50%"><?php echo elgg_echo("custom_index_widgets:showdashboard"); ?></td>
  	     <td>
  	         <?php	echo elgg_view('input/dropdown', array(
          			'name' => 'params[ciw_showdashboard]',
          			'options_values' => array(
          				'yes' => elgg_echo('custom_index_widgets:showdashboard_yes'),
          				'no' => elgg_echo('custom_index_widgets:showdashboard_no'),
        			),
          			'value' => $vars["entity"]->ciw_showdashboard
          		));
          	?>
         </td>
	  </tr>
	  
      </table>
    </div>
</div>
