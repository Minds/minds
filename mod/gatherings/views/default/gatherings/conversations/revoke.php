<div class="conversation-configuration">

	<div class="entypo large-icon">&#128274;</div>
	<h3>Encryption</h3>
	
	<p><b>WARNING: Changing your password will issue new encryption and make your exisiting messages unreadable.</b></p>
	<form action="<?= elgg_get_site_url() ?>gatherings/configuration/keypair-1" method="POST">
		<label for="passphrase">
			Secure password
		</label>
		<input type="password" name="passphrase" placeholder="Enter a secure password - recommended"/>
		<br/>
		<label for="passphrase">
			Confirm password
		</label>
		<input type="password" name="passphrase2" placeholder="enter the same password again..."/>
		<br/>
		<input type="submit" value="Change my key - I'm sure" class="elgg-button elgg-button-action"/>
		
		<?= elgg_view('input/securitytoken') ?>
	</form>
	
</div>
