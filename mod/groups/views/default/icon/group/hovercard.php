<?php

$group = elgg_extract('group', $vars, elgg_get_logged_in_user_entity());

$icon_url = elgg_format_url($group->getIconURL('medium'));
$icon = elgg_view('output/img', array(
    'src' => $icon_url,
    'alt' => $group->name,
    'title' => $group->name,
));
//$overview = elgg_view('user/overview', array('entity' => $group));

global $CONFIG;
$banner = elgg_view('output/img', array(
    'src'=>$CONFIG->cdn_url. "groups/banner/$group->guid",
    'class'=>'hovercard-banner-img'
));
?>
<div class="minds-hovercard" <?php if(isset($vars['show'])){ ?> style="display:block;" <?php } ?>'>
    
    <div class="hovercard-banner">
        <?=$banner ?>
        <div class="top-arrow"><?= $banner ?></div>
        <div class="gradient"></div>
    </div>
    
    <a href="<?= $group->getUrl() ?>">
        <div class="hovercard-container">
            <div class="hovercard-icon">
                <?= $icon ?>
            </div>
            <h3><?=$group->name?></h3>
        </div>
    </a>
    <div class="overview" style="width:auto; margin-left:120px;">
        <?= $group->brief_description ?>
    </div>
    <?php if(!$group->isMember()){ ?>
        <a href="<?= elgg_get_site_url() . "action/groups/join?group_guid={$group->getGUID()}" ?>" class="elgg-button elgg-action-button">
            Join
        </a>
    <?php } ?>
</div>
