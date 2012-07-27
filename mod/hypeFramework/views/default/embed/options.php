<?php

$entity = elgg_extract('entity', $vars, false);

if (!$entity)
    return true;

$simpletype = $entity->simpletype;

if ($simpletype == 'image') {
    $options_size = array(
        elgg_echo('hj:embed:link') => 'link',
        elgg_echo('hj:embed:small') => 'small',
        elgg_echo('hj:embed:medium') => 'medium',
        elgg_echo('hj:embed:large') => 'large'
    );
    $options_float = array(
        elgg_echo('hj:embed:none') => 'none', 
        elgg_echo('hj:embed:left') => 'left',
        elgg_echo('hj:embed:right') => 'right',
    );
} else {
    $options_size = array(
        elgg_echo('hj:embed:link') => 'link'
    );
    $options_float = array(
        elgg_echo('hj:embed:none') => 'none'
    );
}

$form_body = elgg_echo('hj:framework:embed:type');
$form_body .= elgg_view('input/radio', array(
    'align' => 'horizontal',
    'name' => 'type',
    'options' => $options_size
));

$form_body .= elgg_echo("hj:framework:embed:float");
$form_body .= elgg_view('input/radio', array(
    'align' => 'horizontal',
    'name' => 'align', 
    'options' => $options_float
));

$form_body .= elgg_echo("hj:framework:embed:url");
$form_body .= elgg_view('input/url', array(
    'name' => 'url',
    'value' => $entity->getURL(),
));

$form_body .= elgg_view('input/hidden', array(
    'name' => 'title',
    'value' => $entity->title
));

$form_body .= elgg_view('input/hidden', array(
    'name' => 'image', 
    'value' => $entity->getIconURL()
));

$form_body .= "<br />";
$form_body .= elgg_view('input/submit', array(
    'class' => 'hj-embed-options-submit',
    'text' => elgg_echo('Embed')
));


$form = elgg_view('input/form', array(
    'class' => 'hj-embed-options',
    'action' => false,
    'body' => $form_body
));

$options = <<<HTML
    <div id="hj-embed-options-$entity->guid" class="hidden">
        $form
    </div>
HTML;
     
echo $options;
        