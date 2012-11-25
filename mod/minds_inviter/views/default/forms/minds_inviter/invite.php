<?php
	$contacts = $vars['contacts'];
	
	foreach($contacts as $k => $v){
		$options[$k] = $v;;
	}
?>
<script>
function checkAll(bx) {
  var cbs = document.getElementsByTagName('input');
  for(var i=0; i < cbs.length; i++) {
    if(cbs[i].type == 'checkbox') {
      cbs[i].checked = bx.checked;
    }
  }
}
</script>
<div>
	<label><?php echo elgg_echo('minds_inviter:invite'); ?></label><br />
	<?php echo elgg_view('input/checkboxes', array('name'=>'check-all', 'options'=> array('Select All'=>'all'), 'onclick'=>'checkAll(this)'));?>
	<br />
	<?php echo elgg_view('input/checkboxes', array('name'=>'emails','options' => $options)); ?>
</div>

<div class="elgg-foot">

<?php 

echo elgg_view('input/hidden', array('name'=>'user_guid','value' => elgg_get_logged_in_user_guid()));

echo elgg_view('input/submit', array('value' => elgg_echo('minds_inviter:invite:submit')));

?>

</div>
