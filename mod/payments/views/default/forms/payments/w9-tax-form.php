<?php
$data = $vars['data'];
?>
	<h3>W9 Form</h3>
	<div class="table tax-form tax-form-w9">
		<div class="row">
			<div class="cell">
				<h4>Full name</h4>
				 <input type="text" name="name" value="<?= $data['name']?>" required/>
			</div>
			<div class="cell">
				<h4>Business name</h4>
				 <input type="text" name="business_name" value="<?= $data['business_name']?>"/>
				<span class="helper">(optional)</span>
			</div>
		</div>
		
		<div class="row">
			<div class="cell tax-id-no">
				<h4>Taxpayer ID number</h4>
				<input type="text" name="tax_id" value="<?= $data['tax_id']?>" required/>
				<div class="id_type">
					<input type="radio" name="id_type" value="ssn" <?= $data['tax_type'] == 'ssn' ? 'checked=""' : '' ?> required>SSN
					<input type="radio" name="id_type" value="ein" <?= $data['tax_type'] == 'ein' ? 'checked=""' : '' ?> required>EIN
				</div>
			</div>
			<div class="cell">
				<h4>Tax classification</h4>
				 <input type="text" name="tax_classification" value="<?= $data['tax_classification']?>" />
			</div>
		</div>
		
		<div class="row">
			<div class="cell">
				<h4>Address</h4>
				 <input type="text" name="address" value="<?= $data['address']?>" required/>
			</div>
			<div class="cell">
				<h4>City</h4>
				 <input type="text" name="city" value="<?= $data['city']?>" reqyured/>
			</div>
		</div>
		
		<div class="row">
			<div class="cell">
				<h4>State/Zip code</h4>
				 <input type="text" name="zip" value="<?= $data['zip']?>" required/>
			</div>
			<div class="cell">
				
			</div>
		</div>
		
		<div class="row">
			<div class="cell">
				<h4>Your signature</h4>
				 <input type="text" name="signature" value="<?= $data['signature']?>" required/>
				 <span class="helper">Writing your name counts as your signature</span>
			</div>
			<div class="cell">
				<input type="submit" value="Confirm and save" name="w9" class="elgg-button elgg-button-action"/>
			</div>
		</div>
		
		<input type="hidden" name="guid" value="<?= $data['guid']?>"
	</div>