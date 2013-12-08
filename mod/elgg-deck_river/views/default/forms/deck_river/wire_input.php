<?php
/**
 * Wire add form body
 */

$user = elgg_get_logged_in_user_entity();

$bookmarklet = elgg_extract('bookmarklet', $vars, false);

if (!$user) {
	return false;
}

elgg_load_library('deck_river:authorize');

// get and sort accounts
$user_deck_river_accounts_in_wire = json_decode(get_private_setting($user->getGUID(), 'user_deck_river_accounts_in_wire'), true);
$accounts_position = array_flip($user_deck_river_accounts_in_wire['position']);

$all_accounts = deck_river_get_networks_account('all', $user->getGUID(), null, true);

$sorted_accounts = array();
$at_last = array();
foreach($all_accounts as $key => $account) {
	if (isset($accounts_position[$account->getGUID()])) {
		$sorted_accounts[$accounts_position[$account->getGUID()]] = $account;
		unset($all_accounts[$key]);
	} else if (!in_array($account->getGUID(), $user_deck_river_accounts_in_wire['pinned'])) {
		$at_last[] = $account;
		unset($all_accounts[$key]);
	}
}
ksort($sorted_accounts);
$sorted_accounts = array_merge($sorted_accounts, $at_last);
$position = 0;

?>

<div id="thewire-header"<?php if ($bookmarklet) echo ' class="extended"'; ?>>
	<?php if (!$bookmarklet) { ?><div id="thewire-textarea-border"></div><?php } ?>
	<textarea id="thewire-textarea" name="body"><?php echo $bookmarklet; ?></textarea>
	<div class="options hidden">
		<div class="responseTo hidden tooltip s" title="<?php echo elgg_echo('responseToHelper:delete');?>"></div>
		<input class="parent" type="hidden" name="">
		<div id="linkbox" class="hidden phm pvs">
			<?php echo elgg_view('graphics/ajax_loader', array('hidden' => false)); ?>
		</div>
		<div class="url-shortener">
			<?php
				echo elgg_view('output/group', array(
						'group' => array(elgg_view('input/text', array(
								'value' => elgg_echo('deck-river:reduce_url:string')
							)),
							'<span class="elgg-icon elgg-icon-delete hidden tooltip s link" title="' . elgg_echo('deck-river:clean_url') . '"></span>',
							elgg_view('input/button', array(
								'value' => elgg_echo('deck-river:copy_url'),
								'class' => 'elgg-button-action hidden'
							)),
							elgg_view('input/button', array(
								'value' => elgg_echo('deck-river:reduce_url'),
								'class' => 'elgg-button-submit'
							))
						)
					));
			?>
		</div>
	</div>
	<div id="thewire-characters-remaining" class="reverse-border">
		<span>0</span>
	</div>
	<ul id="counters-alert">
		<li class="elgg-count"><span class="hidden gwfb icon-elgg" style="font-size:9px;"></span></li>
		<li class="twitter-count"><span class="hidden gwfb twitter-icon"></span></li>
		<li class="facebook-count"><span class="hidden gwfb facebook-icon"></span></li>
	</ul>
	<div class="thewire-buttons hidden">
		<a href="#" onclick="$('#thewire-textarea').val('').keyup();" class="elgg-icon elgg-icon-trash link tooltip s" title="<?php echo elgg_echo('deck_river:thewire:clean_textarea'); ?>"></a>
		<?php if (!$bookmarklet) echo '<span id="pin-thewire" class="elgg-icon elgg-icon-push-pin link tooltip s plm" title="'.htmlspecialchars(elgg_echo('deck_river:thewire:pinned')).'"></span>'; ?>
	</div>
	<div id="submit-loader" class="hidden response-loader"></div>
	<div class="thewire-button gwfb">
	<?php
		echo elgg_view('input/submit', array(
			'value' => elgg_echo('send'),
			'id' => 'thewire-submit-button',
			'class' => 'noajaxified'
		));
	?>
	</div>
	<div id="thewire-textarea-bottom"></div>
</div>

<div id="thewire-network"<?php if ($bookmarklet) echo ' class="extended"'; ?>>
	<div class="selected-profile">
		<div class="net-profile float mls elgg ggouv">
			<input type="hidden" value="<?php echo $user->getGUID(); ?>" name="networks[]" data-network="elgg">
			<ul>
				<span class="elgg-icon elgg-icon-delete pas hidden"></span>
				<div class="elgg-module-popup hidden">
					<div class="triangle"></div>
					<?php
						echo '<a title="' . $user->username . '" href="#" class="elgg-user-info-popup info-popup">@' . $user->username . '</a>';
					?>
				</div>
			</ul>
			<?php
				echo elgg_view('output/img', array(
					'src' => elgg_format_url($user->getIconURL('tiny')),
					'alt' => $user->username,
					'title' => $user->username,
					'class' => 'float',
				));
			?>
			<span class="network link"></span>
		</div>
		<?php
			foreach ($all_accounts as $account) {
				echo elgg_view_entity($account, array(
					'view_type' => 'in_network_box',
					'pinned' => true,
					'position' => $position
				));
				$position++;
			}
		?>
	</div>
	<?php if (!$bookmarklet) { ?><div class="more_networks gwf tooltip w t5 phs" title="<?php echo elgg_echo('deck-river:add:network'); ?>">+</div><?php } ?>
	<div class="non-pinned clearfloat hidden">
		<div class="helper tooltip w" title="<?php echo htmlspecialchars(elgg_echo('deck-river:add:network:helper')); ?>"><div><?php echo elgg_echo('deck-river:add:network:slide'); ?></div></div>
		<div class="content">
			<div class="net-profiles-wrapper pts float">
				<div class="net-profiles">
				<?php
					foreach ($sorted_accounts as $account) {
						echo elgg_view_entity($account, array(
							'view_type' => 'in_network_box',
							'pinned' => false,
							'position' => $position
						));
						$position++;
					}
				?>
				</div>
			</div>
			<?php if (!$bookmarklet) { ?>
			<div class="footer">
				<ul>
					<li>
					<?php
						echo elgg_view('output/url', array(
							'href' => '#',
							'text' => elgg_echo('deck_river:network:add:account'),
							'class' => 'add_social_network'
						));
					?>
					</li>
					<li>
					<?php
						echo elgg_view('output/url', array(
							'href' => '/authorize/applications/' . $user->username,
							'text' => elgg_echo('deck_river:network:manage_account')
						));
					?>
					</li>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
</div>


