<?php 

$entity = $vars['entity'];
elgg_load_js('player');
elgg_load_css('player');
?>
<style type="text/css">
    #vid {
        width: 640px;
        height: 360px;
    }

    #scrubber {
        position: relative;
        width: 640px;
        height: 20px;
        background-color: black;
    }

    #progress {
        width: 0;
        height: 20px;
        background-color: red;
    }
</style>

<video id="my_video" crossOrigin="" data-thumbSec="<?php echo $entity->thumbnail?>">
	<source src="<?php echo $entity->getSourceURL('360.webm');?>" type="video/webm" />
</video>
<div id="scrubber">
    <div id="progress"></div>
</div>
<canvas id="thecanvas" style="display:none;">
</canvas>
<input type="hidden" id="thumbnailData" name="thumbnailData"/>
<input type="hidden" id="thumbSec" name="thumbSec" />
