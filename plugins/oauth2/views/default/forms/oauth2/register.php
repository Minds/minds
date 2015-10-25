<div>
    <label><?php echo elgg_echo('oauth2:name:label'); ?></label>
    <?php echo elgg_view('input/text', array('name' => 'name', 'value' => $vars['entity']->title)); ?>
</div>

<div>
    <label><?php echo elgg_echo('oauth2:url:label'); ?></label>
    <?php echo elgg_view('input/text', array('name' => 'url', 'value' => $vars['entity']->description)); ?>
</div>

<?php if ($vars['entity']): ?>

    <table>
        <tr>
            <td style="padding: 5px;"><label><?php echo elgg_echo('oauth2:client_id:label'); ?>:</label></td>
            <td style="padding: 5px;"><?php echo $vars['entity']->client_id; ?></td>
            <td></td>
        </tr>
        <tr>
            <td style="padding: 5px;"><label><?php echo elgg_echo('oauth2:client_secret:label'); ?>:</label></td>
            <td id="oauth2-secret-td" style="padding: 5px;"><?php echo $vars['entity']->client_secret; ?></td>
            <td style="padding: 5px;">
                <a title="Regenerate client secret" href="javascript:void(0);" onClick="oauth2.regenerateSecret();">regenerate</a>
            </td>
        </tr>
    </table>

    <input type="hidden" name="guid" value="<?php echo $vars['entity']->guid; ?>">
    <input type="hidden" name="secret" value="<?php echo $vars['entity']->client_secret; ?>" id="oauth2-secret-input">

<?php endif; ?>

<div>
    <?php echo elgg_view('input/submit', array('value' => 'Save')); ?>
</div>

