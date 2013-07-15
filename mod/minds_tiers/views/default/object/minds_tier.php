<?php

$entity = $vars['entity'];


?>
<div class="Minds_product elgg-image-block clearfix">
    <div class="elgg-image">
    </div>
    <div class="elgg-body">
        <h3><?=$entity->title;?> (<?=$entity->product_id;?>): <?=$entity->price;?> <?=$entity->currency;?></h3>
        <p><?=$entity->description;?></p> 
        <?= elgg_view('object/minds_tier/extension', $vars); ?>
        <?php if ($entity->canEdit()) { ?>
            <p><small><?=elgg_view('output/confirmlink', array(
                'text' => 'Delete',
                'href' =>  elgg_add_action_tokens_to_url('action/minds/products/delete') . "&id={$entity->guid}"
            )); ?></small></p>
        <?php } ?>
    </div>
</div>