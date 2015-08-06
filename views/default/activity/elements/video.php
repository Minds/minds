<?php
$data = is_array($vars['data']) ? $vars['data'] : json_decode(json_encode($vars['data']), true);
$entity = get_entity($data['guid']);
?>
<div class="activity-video-post">
    <h3><a href="<?= elgg_get_site_url() ?>archive/view/<?= $data['guid']?>"><?= $entity->title;	?></a></h3>
        <a href="<?= elgg_get_site_url() ?>archive/view/<?= $data['guid']?>">	
                <div class="thumbnail-wrapper archive archive-video t1" style="margin-left:-5%">
			<span></span>
                	<img src="<?= $data['thumbnail_src'] ?>" class="thumbnail"/>
                 </div>
        </a>
</div>
