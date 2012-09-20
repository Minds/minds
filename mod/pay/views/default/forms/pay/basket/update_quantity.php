<?php
/**
 * Pay - update quantity
 *
 * @package Pay
 */

$item_guid = elgg_extract('item_guid', $vars, '');
$quantity = elgg_extract('quantity', $vars, '');


echo elgg_view('input/text',array('name' => 'quantity', 'value'=>$quantity, 'onblur' => 'form.submit();', 'size' => '1'));

echo elgg_view('input/hidden',array('name' => 'item_guid','value'=>$item_guid));
