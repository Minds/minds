<?php

$w9_data = NULL;
$w8_data = NULL;

if($vars['forms']){
	foreach($vars['forms'] as $form){
		$form = new minds\plugin\payments\entities\taxForm($form);
		$data = $form->getEncrypted();
		$data['guid'] = $form->guid;
		if($data['form'] == 'w9'){
			$w9_data = $data;
		} else {
			$w8_data = $data;
		}
	}
}

$w9 = elgg_view_form('payments/w9-tax-form', array('action'=>'settings/payments/payouts'), array('form'=>'w9', 'data'=>$w9_data));
$w8ben = elgg_view_form('payments/w8ben-tax-form', array('action'=>'settings/payments/payouts'), array('form'=>'w8ben', 'data'=>$w8_data));

?>

<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3>Tax details</h3>
	</div>
	
	<div class="table tax-options">
		<a class="option w9 <?= $w9_data ? 'selected' :''?>" href="#">
			US Taxpayers (W-9)
		</a>
		<a class="option w8ben <?= $w8_data ? 'selected' :''?>" href="#">
			Non-US Taxpayers (W-8BEN)
		</a>
	</div>
	
	<div class="w9-form tax-form-container show">
		<?= $w9 ?>
	</div>
	
	<div class="w8ben-form tax-form-container hide">
		<?= $w8ben ?>
	</div>
	
</div>
