<?php
/**
 * Wire add form body
 */
elgg_load_js("deck:js");
$user = elgg_get_logged_in_user_entity();

if (!$user) {
	return false;
}

$all_accounts = deck_river_get_networks_account('all', $user->getGUID(), null, true);
if(count($all_accounts) == 0 || !$all_accounts){
	//create the minds account
	$account = new ElggDeckMinds();
	$account->name = elgg_get_logged_in_user_entity()->name;
	$account->username = elgg_get_logged_in_user_entity()->username;
	$account->id = elgg_get_logged_in_user_entity()->guid;
	$account->node = 'local';
	$guid = $account->save();
	$all_accounts[] = $account;
}

//GET SUB ACCOUNTS
$sub_accounts = array();
foreach($all_accounts as $account){
	if($account->getSubAccounts()){
		foreach($account->getSubAccounts() as $sub_account){
			$sub_accounts[] = $sub_account;
		}
	}
}

//$all_accounts = array_merge($all_accounts, $sub_accounts);

?>

<?php 
echo elgg_view('input/plaintext', array('name'=>'message', 'id'=>'post-input-box', 'placeholder' => elgg_echo('deck-river:post:placeholder')));
?>

<div class="deck-river-accounts">
<?php
$options = array();
$selected = array();
foreach($all_accounts as $account) {
	$label =  elgg_view_entity($account, array(
					'view_type' => 'in_network_box',
					'pinned' => true,
					'position' => $position
				));
	$value = $account->guid;
	if($account->network == 'minds'){
		$selected[$value] = $label;
	} else {
		$options[$value] = $label;
	}
}

//SUB ACCOUNTS (these pass through the main account, but we need to pass the ids...)
foreach($sub_accounts as $sub_account) {
	$parent = get_entity($sub_account->parent_guid);
	$label =  elgg_view_entity($sub_account, array(
					'view_type' => 'in_network_box',
					'pinned' => true,
					'position' => $position
				));
	$value = $sub_account->parent_guid . '/'.$sub_account->id; //assume it's a subpage
	$options[$value] = $label;
}
//echo elgg_view('input/checkboxes', array('name'=>'sub_accounts','options'=>$options));
echo elgg_view('input/dragbox', array('name'=>'accounts', 'selected'=>$selected, 'options'=>$options, 'class'=>elgg_extract('hide_accounts', $vars, false)? 'hidden' : ''));

$file_input = elgg_view('input/file', array('name'=>'attachment', 'class'=>'deck-attachment-button'));

echo <<<HTML
	<div class="deck-attachment-button-override">
		$file_input
	</div>
HTML;

$date = elgg_view('input/date', array('name'=>'schedule_date','value'=>time()));
$time = elgg_view('input/timepicker',array('name'=>'schedule_time','value'=> (time() - strtotime("today")) /60));

echo <<<HTML
	<div class="deck-scheduler-button">&#xe801;</div>
	<div class="deck-scheduler-content">
		$date $time
	</div>
HTML;

echo <<<HTML
	<div class="deck-post-preview">
			<img class="deck-post-preview-icon-img"/>
		<input type="text" name="preview-title" class="deck-post-preview-title"/>
		<textarea type="text" name="preview-description" class="deck-post-preview-description"></textarea>
		<input type="hidden" name="preview-icon" class="deck-post-preview-icon"/>
		<input type="hidden" name="preview-url" class="deck-post-url"/>
	</div>
HTML;


echo elgg_view('input/hidden', array('name'=>'to_guid', 'value'=>elgg_extract('to_guid', $vars, $user->guid)));
echo elgg_view('input/hidden', array('name'=>'access_id', 'value' => elgg_extract('access_id', $vars, ACCESS_PUBLIC)));

echo elgg_view('input/submit', array('value'=>'Post'));
?>
</div>

