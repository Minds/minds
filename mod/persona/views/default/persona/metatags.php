<script src="https://login.persona.org/include.js"></script>
<script>
    
    // Watch functions for Persona logins

	$(function(){

	    // Bind Persona logout function to "log out" button click
	    
	    $('.elgg-menu-item-logout a').click(function(e) {
		navigator.id.logout();
	    });

	    navigator.id.watch({
		loggedInUser: null,
		onlogin: function(assertion) {
		    $.post(
			'<?php echo elgg_get_site_url(); ?>persona/assert',
			{assertion:assertion},
			function(msg) { 
			
<?php

    if (!elgg_is_logged_in()) {

?>
			    console.log('Logged in with Persona!');
			    window.location.replace('<?php echo elgg_get_site_url(); ?>');
<?php

    }

?>
			}
		    );
		},
		onlogout: function() {
		    $.post(
			'<?php echo elgg_get_site_url(); ?>persona/logout',
			{logout:1},
			function(msg) {
			    console.log('Logged out from Persona!');
			}
		    );
		}
	    });

	});
    
</script>