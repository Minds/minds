<?php
        global $CONFIG;

        if (!get_input('url')) die('No URL given');

        $pid = md5(get_input('url').datalist_get('__site_secret__').  elgg_get_logged_in_user_guid()); // Generate from url and site secret plus user, to make it hard to get other peeps comment feeds
        
        
        echo elgg_view('minds_comments/bar', array('type' => 'embed', 'pid' => $pid));
        echo elgg_view('minds_comments/input', array('type' => 'embed', 'pid' => $pid));
        
        ?>
 <script type="text/javascript">
        
    function resizeComments() {
                $('body').css({'background-color':'#fff'});
                $('div.elgg-page').css({'height':($(window).height())+'px', 'width':($(window).width())+'px', 'margin': '0', 'padding' : '0px'});
                $('div.elgg-inner').css({'height':($(window).height())+'px', 'width':($(window).width())+'px', 'margin': '0', 'padding' : '0px'});
		return true;
	    }

	    $(document).ready(function() {
                $('div.elgg-page-topbar').hide();
                $('div.elgg-page-messages').hide();
	       resizeComments();

	    });

	    window.setInterval(resizeComments, 2000);	
</script>