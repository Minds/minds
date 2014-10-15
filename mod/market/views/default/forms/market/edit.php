<?php
$vars = array_merge(array('
			title' => '',
			'description' => '',
			'price',
			'category'
		), $vars);

echo elgg_view('input/text', array('value'=>$vars['title'], 'name'=>'title'));
echo elgg_view('input/plaintext', array('value'=>$vars['description'], 'name'=>'description'));
echo elgg_view('input/text', array('value'=>$vars['price'], 'name'=>'price'));
echo elgg_view('input/dropdown', array('value'=>$vars['category'], 'options'=> \minds\plugin\market\start::getCategories(), 'name'=>'category'));

echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('save')));

