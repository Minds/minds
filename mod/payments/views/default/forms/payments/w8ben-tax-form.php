<?php
$data = $vars['data'];
?>
<h3>W-8BEN Form</h3>
<div class="table tax-form tax-form-w9">
	<div class="row">
		<div class="cell">
			<h4>Full name</h4>
			 <input type="text" name="name" value="<?= $data['name']?>" required/>
		</div>
		<div class="cell">
			<h4>Country</h4>
			 <input type="text" name="country" value="<?= $data['country']?>"required/>
		</div>
	</div>
	
	<div class="row">
		<div class="cell">
			<h4>Your signature</h4>
			 <input type="text" name="signature" value="<?= $data['signature']?>" required/>
			 <span class="helper">Writing your name counts as your signature</span>
		</div>
		<div class="cell">
			<input type="submit" value="Confirm and save" name="w8ben" class="elgg-button elgg-button-action"/>
		</div>
	</div>
	<input type="hidden" name="guid" value="<?= $data['guid']?>"
</div>
	
