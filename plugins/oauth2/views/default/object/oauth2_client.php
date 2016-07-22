<?php

/**
 * oauth2_client object view
 *
 * @author Billy Gunn (billy@arckinteractive.com)
 * @copyright Minds.com 2013
 * @link http://minds.com
 */

$full = elgg_extract('full_view', $vars, false);

if (!$entity = elgg_extract('entity', $vars, false)) {
    return true;
}

$owner = $entity->getOwnerEntity();

$owner_link = elgg_view('output/url', array(
    'href' => $owner->getURL(),
    'text' => $owner->name,
    'is_trusted' => true,
));

?>

<?php if ($full): ?>

    <!-- NOT IMPLEMENTED -->

<?php else: ?>

<div class="elgg-image-block clearfix">

    <div class="elgg-image">
        <?php if (elgg_get_logged_in_user_guid() != $owner->guid): ?>
            <?php echo elgg_view_entity_icon($owner, 'small'); ?>
        <?php else: ?>
            <?php echo elgg_view_entity_icon($entity, 'small'); ?>
        <?php endif; ?>
    </div>

    <div class="elgg-body">

        <ul class="elgg-menu elgg-menu-entity elgg-menu-hz elgg-menu-entity-default" style="display:block;">
            <li class="elgg-menu-item-edit">
                <a href="<?php echo elgg_get_site_url(); ?>oauth2/edit/<?php echo $entity->guid; ?>" title="Edit Application">Edit</a>
            </li>
            <li class="elgg-menu-item-delete">
                <?php echo elgg_view('output/confirmlink', array(
                    'title' => 'Delete Application',
                    'href' => elgg_get_site_url() . 'action/oauth2/delete?guid=' . $entity->guid,
                    'text' => 'Delete'));
                ?>
            </li>
        </ul>

        <h3><?php echo $entity->title; ?></h3>

        <div class="elgg-subtext" style="font-style: normal;">
            <table>
                <?php if (elgg_get_logged_in_user_guid() != $owner->guid): ?>
                    <tr>
                        <td style="padding: 2px 10px 0 0;">Owner:</td>
                        <td style="padding-top: 2px;"><?php echo $owner_link; ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td style="padding: 2px 10px 0 0;">URL:</td>
                    <td style="padding-top: 2px;"><?php echo $entity->description; ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px 10px 0 0;">Client ID:</td>
                    <td style="padding-top: 2px;"><?php echo $entity->client_id; ?></td>
                </tr>
                <tr>
                    <td style="padding: 2px 10px 0 0;">Secret (<a href="javascript:void(0);" onClick="oauth2.toggleSecret(this);" class="oauth2-toggle-secret">show</a>):</td>
                    <td style="padding-top: 2px;"><span style="display:none;"><?php echo $entity->client_secret; ?></span></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>
