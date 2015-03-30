<?php

echo elgg_view('footer/analytics');

$js = Minds\Core\resources::getLoaded('js', 'footer');
foreach ($js as $script) { ?>
	<script type="text/javascript" src="<?php echo $script['src']; ?>"></script>
<?php
}
?>
<div class="minds-modal-wrapper">
    <div class="minds-modal minds-mobile-popup">
        <div class="inner">
            Download our mobile apps
            <a href="https://play.google.com/store/search?q=pub:Minds, Inc.">
              <img alt="Get it on Google Play"
                     src="https://developer.android.com/images/brand/en_generic_rgb_wo_60.png" />
                     </a>

            <a href="#">
                <img src="https://devimages.apple.com.edgekey.net/app-store/marketing/guidelines/images/badge-download-on-the-app-store.svg"/>
            </a>
        </div>
    </div>
</div>
<script type="text/javascript">
		<?php echo elgg_view('js/initialize_elgg'); ?>
	</script>
