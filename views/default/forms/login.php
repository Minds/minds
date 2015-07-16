<?php
/**
 * Minds Login Formn
 */
global $CONFIG;
//echo "<h4>".$CONFIG->sitename."</h4>";
elgg_load_css('select2');
elgg_load_js('select2');
 
$username =  elgg_view('input/text', array(
	'name' => 'username',
	'class' => 'elgg-autofocus',
	'placeholder' => 'username',
	'autocomplete' => 'off'
));

$cluster = new minds\entities\cluster('master');
$nodes = array(
	elgg_get_site_url() => preg_replace('#^https?://#', '',elgg_get_site_url())
	);
foreach($cluster->getNodes() as $uri => $ts){
	$nodes[$uri] = preg_replace('#^https?://#', '',$uri);
}

$node = elgg_view('input/dropdown', array(
	'name' => 'node',
	//'class' => 'elgg-autofocus',
	'placeholder' => 'node',
	'autocomplete' => 'off',
	'options_values' => $nodes
));

$password =  elgg_view('input/password', array(
	'name' => 'password', 
	'placeholder' => 'password',
	'autocomplete' => 'off'
)); 
	
?>

	<?= $username ?>
	
	<?= $password ?>
	
	<div class="node-select">
		<span class="label">sign in with</span><?= $node ?>
		<p>&nbsp;</p><br/>
	</div>
	
	<ul class="elgg-menu elgg-menu-general login-box mtm">
		<li>
				<input type="checkbox" name="persistent" value="true" checked="checked"/>
				<?php echo elgg_echo('user:persistent'); ?>
		</li>
		<li><a class="forgot_link" href="<?php echo elgg_get_site_url(); ?>forgotpassword">
			<?php echo elgg_echo('user:password:lost'); ?>
		</a></li>
	</ul>
	
<?php
	
	echo elgg_view('login/extend', $vars); 
	
	echo elgg_view('input/submit', array('value' => elgg_echo('login')));

	if (isset($vars['returntoreferer'])) {
		echo elgg_view('input/hidden', array('name' => 'returntoreferer', 'value' => 'true'));
	} else {
	    echo elgg_view('input/hidden', array('name' => 'returnto', 'value' => $_SERVER['HTTP_REFERER']));
	}
