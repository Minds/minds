<?php

$cleditor_label = 'Enable CLEditor (replaces default TinyMCE editor)';
$cleditor = elgg_view('input/dropdown', array(
    'name' => 'params[cleditor]',
    'value' => $vars['entity']->cleditor,
    'options_values' => array('on' => 'Enable', 'off' => 'Disable')
        ));

$settings = <<<__HTML

    <h3>CLEditor</h3>
    <div>
        <p><i>$cleditor_label</i><br>$cleditor</p>
    </div>
    <hr>
</div>
__HTML;

echo $settings;