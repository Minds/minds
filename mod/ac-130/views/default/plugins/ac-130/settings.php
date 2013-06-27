<?php 



$wire_setting = $vars['entity']->wire;
$dashboard_setting = $vars['entity']->dashboard;
$translator_setting = $vars['entity']->translator;
$front = $vars['entity']->front;
$mytitle = $vars['entity']->mytitle;
$mytwitter = $vars['entity']->mytwitter;
?>


 






<p>
  <b>Do you want to display the wire form on top of Activity Page?</b>

<?php

echo elgg_view('input/dropdown',array(
'name' => 'params[wire]', 
'options_values'=> array( '0' => '  ', '1'=>'Yes','2'=>'No'),
'value'=> $wire_setting));

 ?>
</p>

<p>
  <b>Do you want to display Social Dashboard Pro Elite?</b>

<?php

echo elgg_view('input/dropdown',array(
'name' => 'params[dashboard]', 
'options_values'=> array( '0' => '  ', '1'=>'Yes','2'=>'No'),
'value'=> $dashboard_setting));

 ?>
</p>

<p>
  <b>Do you want to activate SW Social Translator?</b>

<?php

echo elgg_view('input/dropdown',array(
'name' => 'params[translator]', 
'options_values'=> array( '0' => '  ', '1'=>'Yes','2'=>'No'),
'value'=> $translator_setting));

 ?>
</p>
 <b>Enter your twitter username to display your tweets on frontpage</b>
 <?php

echo elgg_view('input/text', array(
    'name'  => 'params[mytwitter]',
    'value' => $mytwitter,
));


 ?>
</br>
</br>
</br>
<p>
 <b>Enter welcome Title on frontpage</b>
 <?php

echo elgg_view('input/text', array(
    'name'  => 'params[mytitle]',
    'value' => $mytitle,
));


 ?>
</br>
<p>
 <b>Enter welcome text on frontpage</b>
 <?php

echo elgg_view('input/longtext', array(
    'name'  => 'params[front]',
    'value' => $front,
));


 ?>
 