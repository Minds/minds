<?php

$guid = get_input('e');

$html = elgg_view_entity(get_entity($guid));
if ($html) {
    $output['data'] = $html;
    print(json_encode($output));
} else {
    register_error('hj:framework:widget:update:error');
}
return true;
