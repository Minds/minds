<?php if(0){?><script><?php } ?>
elgg.provide('minds.crypt');

minds.crypt.init = function() {
	
	var privateKey = $('.privateKey').html();
	var encrypted = $('.encrypted').html();
	
	// Decrypt with the private key...
	var decrypt = new JSEncrypt();
	decrypt.setPrivateKey(privateKey);
	var uncrypted = decrypt.decrypt(encrypted);
	
	console.log(uncrypted);
	
}

elgg.register_hook_handler('init', 'system', minds.crypt.init);

