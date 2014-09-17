<?php

$column_guid = get_input('column_guid');

$column = get_entity($column_guid);

if($column->canEdit()){
	return $column->delete();
}
