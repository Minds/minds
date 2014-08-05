<?php

$tab_guid = get_input('tab_guid');

$tab = get_entity($tab_guid);

return $tab->delete();
