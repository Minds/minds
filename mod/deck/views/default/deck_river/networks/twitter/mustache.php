<!-- Templates for elgg river twitter item -->
<script id="elgg-river-twitter-template" type="text/template"><li class="elgg-list-item item-twitter-{{id_str}}"
		data-timeid="{{id_str}}"
		data-username="{{user.screen_name}}"
		data-id="{{id_str}}"
		data-object_guid="{{id_str}}"
		data-text="{{text}}"
		>
		<div class="elgg-image-block elgg-river-item clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="twitter-user-info-popup info-popup" title="{{user.screen_name}}">
						<img title="{{user.screen_name}}" alt="{{user.screen_name}}" src="{{user.profile_image_url_https}}">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				{{^thread}}
				<!--<ul class="elgg-menu elgg-menu-river elgg-menu-hz elgg-menu-river-default">
					<li class="elgg-menu-item-response">
						<a href="#" title="<?php echo elgg_echo('reply'); ?>" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-response"></span></a>
					</li>
					<li class="elgg-submenu prs link">
						<span class="elgg-icon elgg-icon-retweet float"></span>
						<ul class="elgg-module-popup hidden">
							<li class="elgg-menu-item-retweet-twitter"><a href="#" twitter_action data-method="post_statusesRetweet{{id_str}}" data-options="{&quot;id&quot;: &quot;{{id_str}}&quot;}"><span class="elgg-icon elgg-icon-twitter"></span><?php echo elgg_echo('retweet'); ?></a>
							<li class="elgg-menu-item-retweet"><a href="#"><span class="elgg-icon elgg-icon-retweet"></span><?php echo elgg_echo('retweet_by_wire'); ?></a>
						</ul>
					</li>
					{{{menu.default}}}
					<li class="elgg-submenu">
						<span class="elgg-icon elgg-icon-hover-menu link gwf"></span>
						<ul class="elgg-module-popup hidden">
							{{#submenu}}
							<li class="elgg-menu-item-{{name}}"><a href="#"{{#method}} twitter_action data-method="{{method}}" data-options="{{options}}" {{/method}}><span class="elgg-icon elgg-icon-{{name}}"></span>{{{content}}}</a>
							{{/submenu}}
						</ul>
					</li>
				</ul>
				{{/thread}}-->
				<div class="elgg-river-summary prl">
					<span class="twitter-user-info-popup info-popup" title="{{user.screen_name}}">{{user.screen_name}}</span><br/>
					<span class="elgg-river-timestamp">
						<a href="https://twitter.com/{{user.screen_name}}/status/{{id_str}}" target="_blank">
							<span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{created_at}}" time="{{posted}}">{{friendly_time}}</acronym>
							</span>
						</a>
						{{#source}}<?php echo elgg_echo('deck_river:via'); ?>&nbsp;{{{source}}}{{/source}}
					</span>
				</div>
				<div class="elgg-river-message">{{{message}}}</div>
				{{#responses}}
				<!--<div class="elgg-river-responses">
					{{#responses.retweet}}
						<span class="elgg-icon elgg-icon-retweet-sub float gwfb{{#retweeted}} retweeted{{/retweeted}}"></span>
						<span class="phs float">{{{responses.retweet}}}</span>
					{{/responses.retweet}}
					{{#responses.favorite}}
						<span class="elgg-icon elgg-icon-star-sub float gwfb{{#favorited}} favorited{{/favorited}}"></span>
						<span>{{favorite_count}}&nbsp;{{responses.favorite}}</span>
					{{/responses.favorite}}
					{{#responses.reply}}
					{{#responses.retweet}}<br/>{{/responses.retweet}}{{#responses.favorite}}{{^responses.retweet}}<br/>{{/responses.retweet}}{{/responses.favorite}}<div class="response-loader float clearfloat hidden"></div>
					<span class="elgg-icon elgg-icon-speech-bubble-alt prs float gwfb"></span>
					<a href="#" class="thread float prm" data-thread="{{id_str}}" data-network="twitter"><?php echo elgg_echo('deck_river:thread'); ?></a>
					{{/responses.reply}}
				</div>-->
				{{/responses}}
			</div>
		</div>
</li></script>



<!-- Template to choose twitter accounts -->
<script id="choose-twitter-account-template" type="text/template">
	<li>
	<div class="elgg-image-block elgg-river-item clearfix">
		<div class="elgg-image">
			<div class="elgg-avatar elgg-avatar-small">
				<div>
					<img title="{{name}}" alt="{{name}}" src="https://twitter.com/api/users/profile_image/{{name}}?size=normal">
				</div>
			</div>
		</div>
		<div class="elgg-body">
			<a style="font-weight:bold;" href="#" twitter_action data-method="{{method}}" data-twitter_account="{{account}}" data-options="{{options}}">{{name}}</a>
		</div>
	</div>
	</li>
</script>



<!-- Template for Twitter user profile popup -->
<script id="twitter-user-profile-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected"><a href="#{{id}}-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
		<li><a href="#{{id}}-get_statusesUser_timeline"><?php echo elgg_echo('activity'); ?></a></li>
		<li><a href="#{{id}}-get_searchTweets"><?php echo elgg_echo('river:mentions'); ?></a></li>
		<li><a href="#{{id}}-get_favoritesList">{{favourites_count}}&nbsp;<?php echo elgg_echo('favorites'); ?></a></li>
	</ul>
	<ul class="elgg-body">
		<li id="{{id}}-info-profile">
			<div class="elgg-avatar elgg-avatar-large float prm">
				<a href="https://twitter.com/{{screen_name}}" title="{{screen_name}}" rel="nofollow" target="_blank">
					<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
					<div class="avatar-wrapper center">
						<img width="200px" title="{{screen_name}}" alt="{{screen_name}}" src="{{profile_image_url}}">
					</div>
				</a>
			</div>
			<div class="plm">
				<h1 class="pts mbs">{{name}}</h1>
				<h2><a href="#" class="twitter-user-info-popup info-popup mbs" style="font-weight:normal;" title="{{screen_name}}">@{{screen_name}}</a></h2>
				<div>{{{description}}}</div>
				<div class="output-group mtm">
					{{^following}}
					<a class="elgg-button elgg-button-action" href="#" twitter_action data-method="post_friendshipsCreate" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;}">
						<?php echo elgg_echo('deck_river:twitter:follow'); ?>
					</a>
					{{/following}}
					{{#following}}
					<a class="elgg-button elgg-button-action" href="#" twitter_action data-method="post_friendshipsDestroy" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;}">
						<?php echo elgg_echo('deck_river:twitter:unfollow'); ?>
					</a>
					{{/following}}
					<ul class="elgg-button elgg-button-dropdown elgg-submenu">
						<ul class="elgg-menu elgg-module-popup hidden" style="width: 160px;">
							{{^following}}
							<li><a href="#" twitter_action data-method="post_friendshipsDestroy" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;}"><?php echo elgg_echo('deck_river:twitter:unfollow'); ?></a></li>
							{{/following}}
							{{#following}}
							<li><a href="#" twitter_action data-method="post_friendshipsCreate" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;}"><?php echo elgg_echo('deck_river:twitter:follow'); ?></a></li>
							{{/following}}
							<li><a href="#" twitter_action data-method="post_listsMembersCreate" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;, &quot;list_id&quot;: &quot;&quot;}"><?php echo elgg_echo('deck_river:twitter:add_to_list'); ?></a></li>
							<li><a href="#" twitter_action data-method="post_listsMembersDestroy" data-options="{&quot;user_id&quot;: &quot;{{id}}&quot;, &quot;list_id&quot;: &quot;&quot;}"><?php echo elgg_echo('deck_river:twitter:remove_from_list'); ?></a></li>
						</ul>
					</ul>
				</div>
			</div>
			<div id="profile-details" class="elgg-body pll">
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{followers_count}}</div><?php echo elgg_echo('friends:followers'); ?></li>
					<li><div class="stats">{{friends_count}}</div><?php echo elgg_echo('friends:following'); ?></li>
					<li><div class="stats">{{listed_count}}</div><?php echo elgg_echo('deck_river:twitter:lists'); ?></li>
					<li><div class="stats">{{statuses_count}}</div><?php echo elgg_echo('item:object:thewire'); ?></li>
				</ul>
				<div class="even">
					<b><?php echo elgg_echo('Twitter'); ?> :</b> <a class="external" target="_blank" href="https://twitter.com/{{screen_name}}">https://twitter.com/{{screen_name}}</a>
				</div>
				{{#url}}
				<div class="even">
					<b><?php echo elgg_echo('site'); ?> :</b> {{{url}}}
				</div>
				{{/url}}
				{{#location}}
				<div class="even">
					<b><?php echo elgg_echo('profile:field:location'); ?> :</b> {{location}}
				</div>
				{{/location}}
				{{#lang}}
				<div class="even">
					<b><?php echo elgg_echo('user:set:language'); ?> :</b> {{lang}}
				</div>
				{{/lang}}
				<div class="even">
					<b><?php echo elgg_echo('profile:time_created'); ?> :</b> {{created_at}}
				</div>
			</div>
		</li>
		<li id="{{id}}-get_statusesUser_timeline" class="column-river hidden" >
			<ul class="column-header hidden" data-network="twitter" data-river_type="twitter_OAuth" data-params="{&quot;method&quot;: &quot;get_statusesUser_timeline&quot;, &quot;user_id&quot;: &quot;{{id}}&quot;, &quot;count&quot;: &quot;100&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
		<li id="{{id}}-get_searchTweets" class="column-river hidden">
			<ul class="column-header hidden" data-network="twitter" data-river_type="twitter_OAuth" data-params="{&quot;method&quot;: &quot;get_searchTweets&quot;, &quot;q&quot;: &quot;@{{screen_name}}&quot;, &quot;count&quot;: &quot;100&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
		<li id="{{id}}-get_favoritesList" class="column-river hidden">
			<ul class="column-header hidden" data-network="twitter" data-river_type="twitter_OAuth" data-params="{&quot;method&quot;: &quot;get_favoritesList&quot;, &quot;user_id&quot;: &quot;{{id}}&quot;, &quot;count&quot;: &quot;100&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>
