<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 */
$password = $password2 = '';
$username = get_input('u');
$email = get_input('e');
$name = get_input('n');

if (elgg_is_sticky_form('register')) {
    extract(elgg_get_sticky_values('register'));
    elgg_clear_sticky_form('register');
}
?>

<div class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="inputName"><?php echo elgg_echo('name'); ?></label>
        <div class="controls">
            <input autocomplete="off" value="<?php echo $name; ?>" type="text" name="name" id="inputName" placeholder="<?php echo elgg_echo('name'); ?>" required/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputEmail"><?php echo elgg_echo('email'); ?></label>
        <div class="controls">
            <input autocomplete="off" value="<?php echo $email; ?>" type="email" name="email" id="inputEmail" placeholder="<?php echo elgg_echo('email'); ?>" required/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputUsername"><?php echo elgg_echo('username'); ?></label>
        <div class="controls">
            <input autocomplete="off" value="<?php echo $username; ?>" type="text" name="username" id="inputUsername" placeholder="<?php echo elgg_echo('username'); ?>" required/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword"><?php echo elgg_echo('password'); ?></label>
        <div class="controls">
            <input type="password" name="password" id="inputPassword" placeholder="<?php echo elgg_echo('password'); ?>" required/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword2"><?php echo elgg_echo('passwordagain'); ?></label>
        <div class="controls">
            <input type="password" name="password2" id="inputPassword2" placeholder="<?php echo elgg_echo('passwordagain'); ?>" required/>
        </div>
    </div>
    <?php
    echo elgg_view('input/captcha', $vars);
    echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
    echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
    echo elgg_view('register/extend', $vars);
    ?>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn"><?php echo elgg_echo('register'); ?></button>
        </div>
    </div>
</div>

