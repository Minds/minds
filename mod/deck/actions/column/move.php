<?php

$tab_guid = get_input('tab');
$column_guid = get_input('column');
$position = get_input('position');

$tab = get_entity($tab_guid);
$column = get_entity($column_guid);
$column->position = $position;
$column->save();

$columms = $tab->getColumns();

usort($columns, create_function('$a,$b','return (int)$a->position > (int)$b->position;'));

$position = 0;
foreach($columns as $column){
	$column->position = $position;
	$position++;
	$column->save();
}
