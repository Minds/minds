<?php

global $CONFIG;


$node_guid = get_input('guid');
$node = new MindsNode($node_guid);
$node->delete();
