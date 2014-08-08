<?php
/**
 * Linkedin column settings page
 */
$selected = $vars['selected'];
$column = $vars['column'];
$tab = $vars['tab'];

$user = elgg_get_logged_in_user_entity();

$class = ($selected != 'linkedin') ? ' hidden' : '';
echo '<div class="tab linkedin' . $class . '"><ul class="box-settings phm"><li>';

// get linkedin account
$linkedin_account = deck_river_get_networks_account('linkedin_account', $user->guid, null, true);

function displayLinkedInAccount($account, $phrase, $class = null) {
	$site_name =  elgg_get_site_entity()->name;
	$linkedin_user = $account->screen_name;
	$linkedin_avatar = 'http://www.linkedin.com/profile/view?id=' . $account->user_id;

	// User linkedin block
	$img = elgg_view('output/img', array('src' => $linkedin_avatar, 'alt' => $linkedin_user, 'class' => 'linkedin-user-info-popup info-popup', 'title' => $linkedin_user, 'width' => '24', 'height' => '24', ));
	$linkedin_name = '<div class="elgg-river-summary"><span class="linkedin-user-info-popup info-popup" title="' . $linkedin_user . '">' . $linkedin_user . '</span>';
	$linkedin_name .= '<br/><span class="elgg-river-timestamp">';
	$linkedin_name .= elgg_view('output/url', array('href' => 'http://www.linkedin.com/profile/view?id=' . $account->user_id, 'text' => 'http://www.linkedin.com/profile/view?id=' . $account->user_id, 'target' => '_blank', 'rel' => 'nofollow'));
	$linkedin_name .= '</span></div>';
	$linkedin_name = elgg_view_image_block($img, $linkedin_name);

	return elgg_view_module('info', '<span class="elgg-river-timestamp">' . $phrase . '</span>', $linkedin_name, array('class' => 'float ' . $class));
}

$options_values = array(	//'get_searchTweets' => elgg_echo('deck_river:linkedin:feed:search:tweets'),
							//'get_searchTweets-popular' => elgg_echo('deck_river:linkedin:feed:search:popular'),
							'networkUpdates' => elgg_echo('deck_river:linkedin:feed:network'),
							'memberUpdates' => elgg_echo('deck_river:linkedin:feed:mine'),
							'groupUpdates' => elgg_echo('deck_river:linkedin:feed:group'),
							'companyUpdates' => elgg_echo('deck_river:linkedin:feed:company'),
							 //'get_listsStatuses' => elgg_echo('deck_river:linkedin:list'),
							 //'get_direct_messages' => elgg_echo('deck_river:linkedin:feed:dm:recept'),
							 //'get_direct_messagesSent' => elgg_echo('deck_river:linkedin:feed:dm:sent'),
							 //'get_favoritesList' => elgg_echo('deck_river:linkedin:feed:favorites')
						);

$add_account = elgg_view('output/url', array(
			'href' => elgg_get_site_url().'authorize/linkedin',
			'text' => elgg_echo('Authorize'),
			'class' => 'elgg-button elgg-button-action mtm',
			'id' => 'authorize-linkedin'
		));


if (!$linkedin_account || count($linkedin_account) == 0) {
	// No account registred, send user off to validate account

	$body = $add_account;
	$output = elgg_view_module('featured', elgg_echo('deck_river:linkedin:authorize:request:title', array($site_name)), $body, array('class' => 'mtl float'));

	$options_values = array(// override values
	'get_searchTweets' => elgg_echo('deck_river:linkedin:feed:search:tweets'), 'get_searchTweets-popular' => elgg_echo('deck_river:linkedin:feed:search:popular'), );

} else if (count($linkedin_account) == 1) {
	// One account registred

	$output = $add_account . displayLinkedInAccount($linkedin_account[0], elgg_echo('deck_river:linkedin:your_account', array($site_name)), 'mtl');
	$output .= elgg_view('input/hidden', array('name' => 'linkedin[account_guid]', 'class' => 'in-module', 'value' => $linkedin_account[0]->getGUID(), 'data-screen_name' => $linkedin_account[0]->screen_name));

} else {
	// more than one account

	if (!isset($user_river_column_options->account))
		$user_river_column_options->account = $linkedin_account[0]->getGUID();
	echo '<label  class="clearfloat float">' . elgg_echo('deck_river:linkedin:choose:account') . '</label><br />';
	foreach ($linkedin_account as $account) {
		$accounts .= displayLinkedInAccount($account, '', 'mtm mbs multi ' . $account->getGUID());
		$accounts_name[$account->getGUID()] = $account->screen_name;
	}
	echo elgg_view('input/dropdown', array('name' => 'linkedin[account_guid]', 'value' => $accounts_name[$account->getGUID()], 'class' => 'in-module', 'options_values' => $accounts_name)) . $add_account;
	echo $accounts;

}

// select feed
echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label>';
echo elgg_view('input/dropdown', array('name' => 'linkedin[method]', 'value' => $selected == 'linkedin' ? $column->method : 'linkedin:search/tweets', 'class' => 'column-type mts clearfloat float', 'options_values' => $options_values));

echo '<li class="get_searchTweets-options get_searchTweets-popular-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
echo elgg_view('input/text', array('name' => 'linkedin-search', 'value' => $user_river_column_options->search));
echo '</li>';

echo '<li class="groupUpdates-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:linkedin:group') . '</label><br />';
echo elgg_view('input/dropdown', array('name' => 'linkedin_group', 'value' => $user_river_column_options->list_id, 'options_values' => array($user_river_column_options->list_id => $user_river_column_options->list_name), 'class' => 'float')) . '<div class="response-loader hidden float" style="margin: 1px 0px 0px 30px;"></div>';
echo '</li>';

echo '<li class="companyUpdates-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:linkedin:company') . '</label><br />';
echo elgg_view('input/dropdown', array('name' => 'linkedin_company', 'value' => $user_river_column_options->list_id, 'options_values' => array($user_river_column_options->list_id => $user_river_column_options->list_name), 'class' => 'float')) . '<div class="response-loader hidden float" style="margin: 1px 0px 0px 30px;"></div>';
echo '</li>';

echo $output;

echo '</li></ul></div>';

unset($output);

?>
<script>
	$('.tab.linkedin select[name="linkedin[method]"]').change(function() {
		var $bs = $(this).closest('.box-settings'),
			$stlg = $bs.find('select[name="linkedin_group"]'),
			$stlc = $bs.find('select[name="linkedin_company"]'),
			network_account = $bs.find('.in-module').val();

		// Get lists for linkedIn group
		if ($(this).val() == 'groupUpdates' && !($stlg.data('list_loaded') == network_account) && $stlg.parent().hasClass('hidden')) {
			$bs.find('.groupUpdates-options').removeClass('hidden');
			elgg.action('deck_river/network/action', {
				data: {
					column_guid: $(this).closest('form').find('input[name="column_guid"]').val(),
					params: 'people/~/group-memberships:(group:(id,name))',
					method: 'get'
				},
				dataType: 'json',
				success: function(json) {
					$.each(json.output.values, function(i, e) {
						if (!$stlg.find('option[value="'+e.group.id+'"]').length) $stlg.append($('<option>').val(e.group.id).html(e.group.name));
					});
					$bs.find('.groupUpdates-options div').addClass('hidden');
					$stlg.data('list_loaded', network_account);
				},
				error: function() {
					return false;
				}
			});
		} else if ($(this).val() == 'companyUpdates' && !($stlc.data('list_loaded') == network_account) && $stlc.parent().hasClass('hidden')) {
			$bs.find('.companyUpdates-options').removeClass('hidden');
			elgg.action('deck_river/network/action', {
				data: {
					column_guid: $(this).closest('form').find('input[name="column_guid"]').val(),
					params: 'people/~/following/companies',
					method: 'get'
				},
				dataType: 'json',
				success: function(json) {
					if (json.output._total != 0) {
						$.each(json.output.values, function(i, e) {
							if (!$stlc.find('option[value="'+e.id+'"]').length) $stlc.append($('<option>').val(e.id).html(e.name));
						});
						$bs.find('.companyUpdates-options div').addClass('hidden');
						$stlc.data('list_loaded', network_account);
					} else {
						elgg.register_error(elgg.echo('linkedin:companies:follow_none'));
					}
				},
				error: function() {
					return false;
				}
			});
		}
	});
</script>

