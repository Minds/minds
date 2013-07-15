<?php
        global $CONFIG;
        
        elgg_set_page_owner_guid(get_input('feed_guid'));

        
	set_input('page_type', 'mine');

	// content filter code here
	$entity_type = '';
	$entity_subtype = '';

	require_once($CONFIG->pluginspath ."minds/pages/river.php");
        ?>
 <script type="text/javascript">
        
    function resizeRiver() {
                $('body').css({'background-color':'#fff'});
                $('div.elgg-page').css({'height':($(window).height())+'px', 'width':($(window).width())+'px', 'margin': '0', 'padding' : '0px'});
                $('div.elgg-inner').css({'height':($(window).height())+'px', 'width':($(window).width())+'px', 'margin': '0', 'padding' : '0px'});
		return true;
	    }

	    $(document).ready(function() {
                $('div.elgg-page-topbar').hide();
                $('div.elgg-page-messages').hide();
	       resizeRiver();

	    });

	    window.setInterval(resizeRiver, 2000);	
</script>