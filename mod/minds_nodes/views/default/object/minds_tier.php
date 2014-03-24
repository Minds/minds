<?php

$entity = $vars['entity'];

$expires_lookup = array(
    -1 => 'NEVER',
    86400 => 'One Day',
    604800 => 'One Week',
    2419200 => 'One Month (28 days)',
    31536000 => 'One Year'
);


?>
<div class="Minds_product elgg-image-block clearfix">
    <div class="elgg-image">
    </div>
    <div class="elgg-body">
        <h3><?php echo $entity->title;?> (<?php echo $entity->product_id;?>): <?php echo $entity->price;?> <?php echo $entity->currency;?>, Expires after: <?php echo $expires_lookup[$entity->expires]; ?></h3>
        <p><?php echo $entity->description;?></p> 
        <?php echo  elgg_view('object/minds_tier/extension', $vars); ?>
        <?php if ($entity->canEdit()) { ?>
        <p><small><?php echo elgg_view('output/url', array('text' => 'Edit', 'href' => current_page_url() . '?guid=' . $entity->guid)); ?> : <?php echo elgg_view('output/confirmlink', array(
                'text' => 'Delete',
                'href' =>  elgg_add_action_tokens_to_url('action/minds/minds_tiers/delete') . "&id={$entity->guid}"
            )); ?></small></p>
        <?php } ?>
    </div>
</div>