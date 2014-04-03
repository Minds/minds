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
	<div class='social'>
		<?php echo elgg_view('minds_social/login');?>
	</div>
	- OR - 
	
	<div class="blob" style="margin: 16px 0; font-style: italic; color: #888;">
		Anonymous accounts are fine with us. We encourage people to use of TOR for encrypted and secure browsing.
	</div>
	<!--<div class="mtm">
		<label><?php echo elgg_echo('name'); ?></label><br />
		<?php
		echo elgg_view('input/text', array(
			'name' => 'n',
			'value' => $name,
			'class' => 'elgg-autofocus',
		));
		?>
	</div>-->
	<div>
		<label><?php echo elgg_echo('email'); ?></label><br />
		<?php
			echo elgg_view('input/text', array(
				'name' => 'e',
				'value' => $e,
				'autocomplete' => 'off'
			));
		?>
	</div>
	<div>
		<label><?php echo elgg_echo('username'); ?></label><br />
		<?php
			echo elgg_view('input/text', array(
				'name' => 'u',
				'value' => $u,
				'autocomplete' => 'off'
			));
		?>
	</div>
	<div>
		<label><?php echo elgg_echo('password'); ?></label><br />
		<?php
			echo elgg_view('input/password', array(
				'name' => 'p',
				'value' => $p,
				'autocomplete' => 'off'
			));
		?>
	</div>
	<!--<div>
		<label><?php echo elgg_echo('passwordagain'); ?></label><br />
		<?php
			echo elgg_view('input/password', array(
				'name' => 'p2',
				'value' => $password2,
			));
		?>
	</div>-->

	<?php
		// view to extend to add more fields to the registration form
		echo elgg_view('register/extend', $vars);

		// Add captcha hook
		echo elgg_view('input/captcha', $vars);

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'friend_guid', 'value' => $vars['friend_guid']));
echo elgg_view('input/hidden', array('name' => 'invitecode', 'value' => $vars['invitecode']));
if(isset($vars['returntoreferer']))
    echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'y'));
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('register')));
echo '</div>';

