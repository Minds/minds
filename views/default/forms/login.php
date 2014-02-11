<?php
/**
 * Elgg login form
 *
 * @package Elgg
 * @subpackage Core
 */
?>

	<?php echo elgg_view('input/text', array(
		'name' => 'username',
		'class' => 'elgg-autofocus',
		'placeholder' => 'username'
		));
	?>
	<?php echo elgg_view('input/password', array('name' => 'password', 'placeholder' => 'password')); ?>

<?php echo elgg_view('login/extend', $vars); ?>
	
	<?php echo elgg_view('input/submit', array('value' => elgg_echo('login'))); ?>
	
	<?php 
	if (isset($vars['returntoreferer'])) {
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	}
	?>

	<ul class="elgg-menu elgg-menu-general login-box mtm">
		
	<?php
			echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register">' . elgg_echo('register') . '</a></li>';
			echo '<li><a class="registration_link" href="' . elgg_get_site_url() . 'register/node">' . elgg_echo('register:node') . '</a></li>';
	?>
	</ul>
	<ul class="elgg-menu elgg-menu-general login-box mtm">
		<li>
				<input type="checkbox" name="persistent" value="true" checked="checked"/>
				<?php echo elgg_echo('user:persistent'); ?>
		</li>
		<li><a class="forgot_link" href="<?php echo elgg_get_site_url(); ?>forgotpassword">
			<?php echo elgg_echo('user:password:lost'); ?>
		</a></li>
	</ul>
