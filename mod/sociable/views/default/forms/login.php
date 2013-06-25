<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>
<div class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="inputUsername"><?php echo elgg_echo('loginusername'); ?></label>
        <div class="controls">
            <input type="text" name="username" id="inputUsername" placeholder="<?php echo elgg_echo('loginusername'); ?>" required>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword"><?php echo elgg_echo('password'); ?></label>
        <div class="controls">
            <input type="password" name="password" id="inputPassword" placeholder="<?php echo elgg_echo('password'); ?>" required>
        </div>
    </div>
    <?php echo elgg_view('login/extend', $vars); ?>




    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <input type="checkbox" name="persistent" value="true"> <?php echo elgg_echo('user:persistent'); ?>
            </label>
            <button type="submit" class="btn btn-success"><?php echo elgg_echo('login'); ?></button>
            <a class="btn" href="<?php echo elgg_get_site_url(); ?>forgotpassword"><?php echo elgg_echo('user:password:lost'); ?></a>
        </div>
    </div>
</div>

<?php
if (isset($vars['returntoreferer'])) {
    echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
}
?>