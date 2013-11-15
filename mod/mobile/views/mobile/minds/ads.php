<?php 

$type = elgg_extract('type', $vars, 'content-side');

/*echo '<div class="mobile-ad"><script type="text/javascript"><!--
google_ad_client = "ca-pub-9303771378013875";
google_ad_slot = "8589966821";
google_ad_width = 320;
google_ad_height = 50;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>';*/
if($type == 'taboola'){
	echo "<div id='taboola-below-main-column'></div>
<script type='text/javascript'>

window._taboola = window._taboola || [];

_taboola.push({mode:'thumbs-2r',
container:'taboola-below-main-column',
placement:'below-main-column'});

</script>

<div id='taboola-text-2-columns-mix'></div>
<script type='text/javascript'>

        window._taboola = window._taboola || [];

        _taboola.push({mode:'text-links-2c', container:'taboola-text-2-columns-mix', placement:'text-2-columns', target_type:'mix'}); </script>";
} else {

echo '<div class="mobile-ad"><script type="text/javascript"><!--
                google_ad_client = "ca-pub-9303771378013875";
                /* Minds large block */
                google_ad_slot = "5788264423";
                google_ad_width = 336;
                google_ad_height = 280;
                //-->
                </script>
                <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                </script></div>';

}
