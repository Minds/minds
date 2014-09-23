<?php


echo "<div class=\"subscriptions-add\">";

//echo "<h3>Subscribe</h3>";
echo "<p>Enter the username of who you wish to subscribe to. If they belong to another network enter @domain.tdl at the end</p>";

echo elgg_view('input/autocomplete', array('name'=>'address', 'value'=>$address, 'placeholder'=>'Username', 'data-type'=>'user', 'class'=>'user-lookup'));

echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('Subscribe')));

echo '</div>';
