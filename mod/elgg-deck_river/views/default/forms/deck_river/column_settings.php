<?php

// Get tab and column
$tab = elgg_extract('tab', $vars, null);
$column = elgg_extract('column', $vars, null);

if (!$tab || !$column) {
	return;
}

elgg_load_library('deck_river:authorize');

// Get the settings of the current user
$user = elgg_get_logged_in_user_entity();
$user_river_options = json_decode($user->getPrivateSetting('deck_river_settings'));

$site_name = elgg_get_site_entity()->name;

if ($column == 'new') {
	foreach ($user_river_options->$tab as $key => $item) {
		$n[] = preg_replace('/[^0-9]+/', '', $key);
	}
	$column = 'column-' . (max($n)+1);
	$new = true;
}
$user_river_column_options = $user_river_options->$tab->$column;
$column_title = $user_river_column_options->title;
?>

<?php echo elgg_view('input/hidden', array('name' => 'column', 'value' => $column)); ?>
<?php echo elgg_view('input/hidden', array('name' => 'tab', 'value' => $tab)); ?>

<div id='deck-column-settings' class='pas'>
	<?php
		$selected = $new ? 'elgg' : $user_river_column_options->network;
		if (!$selected) $selected = 'elgg';
		$params = array(
			'type' => 'vertical',
			'class' => 'networks float',
			'tabs' => array(
				array('title' => $site_name, 'link_class' => 'elgg', 'url' => "#", 'selected' => $selected == 'elgg' ? true : false),
				array('title' => elgg_echo('Twitter'), 'link_class' => 'twitter', 'url' => "#", 'selected' => $selected == 'twitter' ? true : false),
				array('title' => elgg_echo('Facebook'), 'link_class' => 'facebook', 'url' => "#", 'selected' => $selected == 'facebook' ? true : false),
			)
		);
		echo elgg_view('navigation/tabs', $params);
	?>

	<div class="tab elgg<?php if ($selected != 'elgg') echo ' hidden'; ?>">
		<ul class='box-settings phm'>
			<li>
				<label><?php echo elgg_echo('deck_river:type'); ?></label><br />
				<?php
					$set = str_replace("&gt;", ">", elgg_get_plugin_setting('column_type', 'elgg-deck_river'));
					if (!$set) $set = elgg_echo('deck_river:settings:column_type:default');
					//eval("\$options_values = $set;");
					echo elgg_view('input/dropdown', array(
						'name' => 'type',
						'value' => $user_river_column_options->type,
						'class' => 'column-type mts',
						'options_values' => $options_values
					)); ?>
			</li>
			<li class='search-options hidden pts'>
				<label><?php echo elgg_echo('deck_river:search'); ?></label><br />
				<?php echo elgg_view('input/text', array(
					'name' => 'search',
					'value' => $user_river_column_options->search
				)); ?>
			</li>
			<li class='group-options group_mention-options hidden pts'>
				<label><?php echo elgg_echo('group'); ?></label><br />
				<?php
					echo elgg_view('input/autocomplete', array(
						'name' => 'group',
						'value' => $user_river_column_options->group,
						'match_on' => 'groups'
					));
				?>
			</li>
		</ul>
	</div>


	<?php // TWITTER
		$twitter_consumer_key = elgg_get_plugin_setting('twitter_consumer_key', 'elgg-deck_river');
		$twitter_consumer_secret = elgg_get_plugin_setting('twitter_consumer_secret', 'elgg-deck_river');
		if ($twitter_consumer_key && $twitter_consumer_secret) {
			$class = ($selected != 'twitter')  ? ' hidden': '';
			echo '<div class="tab twitter' . $class . '"><ul class="box-settings phm"><li>';

			// get twitter account
			$twitter_account = deck_river_get_networks_account('twitter_account', $user_guid, null, true);

			function displayTwitterAccount($account, $phrase, $class = null) {
				$site_name = elgg_get_site_entity()->name;
				$twitter_user = $account->screen_name;
				$twitter_avatar = 'http://twitter.com/api/users/profile_image/' . $account->screen_name . '?size=mini'; // $account->avatar,

				// User twitter block
				$img = elgg_view('output/img', array(
					'src' => $twitter_avatar,
					'alt' => $twitter_user,
					'class' => 'twitter-user-info-popup info-popup',
					'title' => $twitter_user,
					'width' => '24',
					'height' => '24',
				));
				$twitter_name = '<div class="elgg-river-summary"><span class="twitter-user-info-popup info-popup" title="' . $twitter_user . '">' . $twitter_user . '</span>';
				$twitter_name .= '<br/><span class="elgg-river-timestamp">';
				$twitter_name .= elgg_view('output/url', array(
					'href' => 'http://twitter.com/' . $twitter_user,
					'text' => 'http://twitter.com/' . $twitter_user,
					'target' => '_blank',
					'rel' => 'nofollow'
				));
				$twitter_name .= '</span></div>';
				$twitter_name = elgg_view_image_block($img, $twitter_name);

				return elgg_view_module(
					'info',
					'<span class="elgg-river-timestamp">' . $phrase . '</span>',
					$twitter_name,
					array(
						'class' => 'float ' . $class
					)
				);
			}

			$options_values = array(
				'get_searchTweets' => elgg_echo('deck_river:twitter:feed:search:tweets'),
				'get_searchTweets-popular' => elgg_echo('deck_river:twitter:feed:search:popular'),
				'get_statusesHome_timeline' => elgg_echo('deck_river:twitter:feed:home'),
				'get_statusesMentions_timeline' => elgg_echo('river:mentions'),
				'get_statusesUser_timeline' => elgg_echo('deck_river:twitter:feed:user'),
				'get_listsStatuses' => elgg_echo('deck_river:twitter:list'),
				'get_direct_messages' => elgg_echo('deck_river:twitter:feed:dm:recept'),
				'get_direct_messagesSent' => elgg_echo('deck_river:twitter:feed:dm:sent'),
				'get_favoritesList' => elgg_echo('deck_river:twitter:feed:favorites'),
			);

			$add_account = elgg_view('output/url', array(
				'href' => '#',
				'text' => '+',
				'class' => 'add_social_network tooltip s t',
				'title' => elgg_echo('deck_river:network:add:account')
			));

			if (!$twitter_account || count($twitter_account) == 0) { // No account registred, send user off to validate account

				$body = elgg_echo('deck_river:twitter:columnsettings:request');
				$body .= elgg_view('output/url', array(
					'href' => '#',
					'text' => elgg_echo('deck_river:twitter:authorize:request:button'),
					'class' => 'add_social_network elgg-button elgg-button-action mtm',
				));
				$output = elgg_view_module(
					'featured',
					elgg_echo('deck_river:twitter:authorize:request:title', array($site_name)),
					$body,
					array('class' => 'mtl float')
				);

				$options_values = array( // override values
					'get_searchTweets' => elgg_echo('deck_river:twitter:feed:search:tweets'),
					'get_searchTweets-popular' => elgg_echo('deck_river:twitter:feed:search:popular'),
				);

			} else if (count($twitter_account) == 1) { // One account registred

				$output = $add_account . displayTwitterAccount($twitter_account[0], elgg_echo('deck_river:twitter:your_account', array($site_name)), 'mtl');
				$output .= elgg_view('input/hidden', array(
					'name' => 'twitter-account',
					'class' => 'in-module',
					'value' => $twitter_account[0]->getGUID(),
					'data-screen_name' => $twitter_account[0]->screen_name
				));

			} else { // more than one account

				if (!isset($user_river_column_options->account)) $user_river_column_options->account = $twitter_account[0]->getGUID();
				echo '<label  class="clearfloat float">' . elgg_echo('deck_river:twitter:choose:account') . '</label><br />';
				foreach ($twitter_account as $account) {
					$accounts .= displayTwitterAccount($account, '', 'mtm mbs multi ' . $account->getGUID());
					$accounts_name[$account->getGUID()] = $account->screen_name;
				}
				echo elgg_view('input/dropdown', array(
					'name' => 'twitter-account',
					'value' => $user_river_column_options->account,
					'class' => 'in-module',
					'options_values' => $accounts_name
				)) . $add_account;
				echo $accounts;

			}

			// select feed
			echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label>';
			echo elgg_view('input/dropdown', array(
				'name' => 'twitter-type',
				'value' => $selected == 'twitter' ? $user_river_column_options->type : 'twitter:search/tweets',
				'class' => 'column-type mts clearfloat float',
				'options_values' => $options_values
			));

			echo '<li class="get_searchTweets-options get_searchTweets-popular-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
			echo elgg_view('input/text', array(
				'name' => 'twitter-search',
				'value' => $user_river_column_options->search
			));
			echo '</li>';

			echo '<li class="get_listsStatuses-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:twitter:lists') . '</label><br />';
			echo elgg_view('input/dropdown', array(
				'name' => 'twitter-lists',
				'value' => $user_river_column_options->list_id,
				'options_values' => array($user_river_column_options->list_id => $user_river_column_options->list_name),
				'class' => 'float'
			)) . '<div class="response-loader hidden float" style="margin: 1px 0px 0px 30px;"></div>';
			echo '</li>';

			echo $output;

			echo '</li></ul></div>';
		} else {
			echo '<div class="tab twitter' . $class . '"><ul class="box-settings phm">Twitter is not configured</ul></div>';
		}

		unset($output);

		// FACEBOOK
		$facebook_app_id = elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river');
		$facebook_app_secret = elgg_get_plugin_setting('facebook_app_secret', 'elgg-deck_river');
		if ($facebook_app_id && $facebook_app_secret) {
			$class = ($selected != 'facebook')  ? ' hidden': '';
			echo '<div class="tab facebook' . $class . '"><ul class="box-settings phm"><li>';

			// get facebook account
			$facebook_account = array_reverse(deck_river_get_networks_account('facebook_account', $user_guid, null, true));

			function displayFacebookAccount($account, $phrase, $class = null) {
				$site_name = elgg_get_site_entity()->name;
				$facebook_user = $account->name;

				if ($account->icon) { // this is a group
					$link = 'groups/' . $account->name;
					$limited = ' limited';
				} else if ($account->parent_id) { // this is a page
					$link = 'pages/' . $account->name . '/' . $account->user_id;
					$limited = ' limited';
				} else { // this is a facebook user
					$link = $account->username;
					$limited = '';
				}

				$facebook_avatar = $account->icon ? $account->icon : 'https://graph.facebook.com/' . $account->user_id . '/picture';

				// User facebook block
				$img = elgg_view('output/img', array(
					'src' => $facebook_avatar,
					'alt' => $facebook_user,
					'class' => 'facebook-user-info-popup info-popup',
					'title' => $facebook_user,
					'width' => '24',
					'height' => '24',
				));
				$facebook_name = '<div class="elgg-river-summary"><span class="facebook-user-info-popup info-popup" title="' . $account->user_id . '">' . $facebook_user . '</span>';
				$facebook_name .= '<br/><span class="elgg-river-timestamp">';
				$facebook_name .= elgg_view('output/url', array(
					'href' => 'http://facebook.com/' . $link,
					'text' => 'http://facebook.com/' . $link,
					'target' => '_blank',
					'rel' => 'nofollow'
				));
				$facebook_name .= '</span></div>';
				$facebook_name = elgg_view_image_block($img, $facebook_name);

				return elgg_view_module(
					'info',
					"<span class=\"elgg-river-timestamp$limited\">$phrase</span>",
					$facebook_name,
					array(
						'class' => 'float ' . $class
					)
				);
			}

			$options_values = array(
				'home' => elgg_echo('deck_river:facebook:feed:home'),
				'home_fql' => elgg_echo('deck_river:facebook:feed:home_fql'),
				'feed' => elgg_echo('deck_river:facebook:feed'),
				'statuses' => elgg_echo('deck_river:facebook:feed:statuses'),
				'links' => elgg_echo('deck_river:facebook:feed:links'),
				'page' => elgg_echo('deck_river:facebook:feed:page'),
				'search' => elgg_echo('deck_river:facebook:feed:search'),
			);

			$add_account = elgg_view('output/url', array(
				'href' => '#',
				'text' => '+',
				'class' => 'add_social_network tooltip s t',
				'title' => elgg_echo('deck_river:network:add:account')
			));

			if (!$facebook_account || count($facebook_account) == 0) { // No account registred, send user off to validate account

				$body = elgg_echo('deck_river:facebook:columnsettings:request');
				$body .= elgg_view('output/url', array(
					'href' => '#',
					'text' => elgg_echo('deck_river:facebook:authorize:request:button'),
					'class' => 'add_social_network elgg-button elgg-button-action mtm',
				));
				$output = elgg_view_module(
					'featured',
					elgg_echo('deck_river:facebook:authorize:request:title', array($site_name)),
					$body,
					array('class' => 'mtl float')
				);

				$options_values = array( // override values
					'search' => elgg_echo('deck_river:facebook:search'),
				);

			} else if (count($facebook_account) == 1) { // One account registred

				$output = $add_account . displayFacebookAccount($facebook_account[0], elgg_echo('deck_river:facebook:your_account', array($site_name)), 'mtl');
				$output .= elgg_view('input/hidden', array(
					'name' => 'facebook-account',
					'class' => 'in-module',
					'value' => $facebook_account[0]->getGUID(),
					'data-username' => $facebook_account[0]->name
				));

			} else { // more than one account

				if (!isset($user_river_column_options->account)) $user_river_column_options->account = $facebook_account[0]->getGUID();
				echo '<label class="clearfloat float">' . elgg_echo('deck_river:facebook:choose:account') . '</label><br />';
				$accounts_name = array();
				foreach ($facebook_account as $account) {
					$accounts .= displayFacebookAccount($account, '', 'mts mbm multi ' . $account->getGUID());
					if ($account->icon) { // this is a group
						$accounts_name[$account->getGUID()] = elgg_echo('river:group') . ' ' . $account->name;
					} else if ($account->parent_id) { // this is a page
						$accounts_name[$account->getGUID()] = elgg_echo('deck_river:facebook:pages') . ' ' . $account->name;
					} else { // this is a facebook user
						$accounts_name[$account->getGUID()] = $account->name;
					}
				}
				echo elgg_view('input/dropdown', array(
					'name' => 'facebook-account',
					'value' => $user_river_column_options->account,
					'class' => 'in-module',
					'options_values' => $accounts_name
				)) . $add_account;
				echo $accounts;

			}

			// select feed
			echo '<label class="clearfloat float">' . elgg_echo('deck_river:type') . '</label><br />';
			echo elgg_view('input/dropdown', array(
				'name' => 'facebook-type',
				'value' => $selected == 'facebook' ? $user_river_column_options->type : 'home',
				'class' => 'column-type mts clearfloat float',
				'options_values' => $options_values
			));

			// search input
			echo '<li class="search-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:search') . '</label><br />';
			echo elgg_view('input/text', array(
				'name' => 'facebook-search',
				'value' => $user_river_column_options->search
			));
			echo '</li>';

			// select page
			echo '<li class="page-options hidden pts clearfloat"><label>' . elgg_echo('deck_river:select:page') . '</label><br />';
			echo elgg_view('input/text', array(
				'name' => 'facebook-page_name',
				'placeholder' => elgg_echo('Entrez le nom de la page'),
				'value' => $user_river_column_options->page_name,
				'data-original_value' => $user_river_column_options->page_id
			));
			echo elgg_view('input/hidden', array(
				'name' => 'facebook-page_id',
				'value' => $user_river_column_options->page_id
			));
			echo '</li>';

			echo $output;

			echo '</li></ul></div>';
		} else {
			echo '<div class="tab facebook' . $class . '"><ul class="box-settings phm">Facebook is not configured</ul></div>';
		}

	?>

	<div class="elgg-foot ptm">
	<?php
		echo elgg_view('input/submit', array(
			'name' => 'elgg',
			'value' => elgg_echo('save'),
			'class' => $selected == 'elgg' ? 'elgg-button-submit elgg' : 'elgg-button-submit elgg hidden'
		));

		if ($twitter_consumer_key && $twitter_consumer_secret) {
			echo elgg_view('input/submit', array(
				'name' => 'twitter',
				'value' => elgg_echo('save'),
				'class' => $selected == 'twitter' ? 'elgg-button-submit twitter' : 'elgg-button-submit twitter hidden'
			));
		}

		if ($facebook_app_id && $facebook_app_secret) {
			echo elgg_view('input/submit', array(
				'name' => 'facebook',
				'value' => elgg_echo('save'),
				'class' => $selected == 'facebook' ? 'elgg-button-submit facebook' : 'elgg-button-submit facebook hidden'
			));
		}

		if (!$new) {
			echo elgg_view('input/submit', array(
					'name' => 'delete',
					'value' => elgg_echo('delete'),
					'class' => 'elgg-button-delete float-alt',
			));
		}
	?>
	</div>

</div>