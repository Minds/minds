<?php

$ia = elgg_set_ignore_access();


elgg_set_ignore_access($ia);

echo elgg_view('minds_nodes/elements/features', array('hide_free'=>true));
echo elgg_view('input/hidden', array('name'=>'node_guid', 'value'=>$vars['node']->guid));
echo elgg_view('input/hidden', array('name'=>'tier_guid'));
echo elgg_view('input/card', array('hide_cards'=>true));
