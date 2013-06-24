<?php

    // TODO : make tiers customisable

?>
<div class="tiers">
    
    <?= elgg_view('input/tier', array('tier' => 'free')); ?>
    
    <?= elgg_view('input/tier', array('tier' => 'basic', 'selected' => true)); ?>
    
    <?= elgg_view('input/tier', array('tier' => 'awesome')); ?>
    
</div>