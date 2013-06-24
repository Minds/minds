<?php

$tier_id = $vars['tier'];
$name = $tier_id;
$desc = $vars['description'];

?>
<div class="tier">
    <?= $desc; ?>
    
    <div class="tier_selection">
        <input type="radio" name="<?=$name; ?>" value="<?= $tier_id; ?>" <?php if ($vars['selected']) echo 'checked'; ?> />
    </div>
</div>