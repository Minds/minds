<!-- Templates for elgg river facebook item -->
<script id="elgg-river-facebook-template" type="text/template"><li class="elgg-list-item item-facebook-{{id}}" data-object_id="{{object_id}}" data-id="{{id}}" data-username="{{from.name}}">
		<div class="elgg-image-block elgg-river-item clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="facebook-{{#from.category}}page{{/from.category}}{{^from.category}}user{{/from.category}}-info-popup info-popup link" title="{{from.id}}">
						<img title="{{from.name}}" alt="{{from.name}}" src="http://graph.facebook.com/{{from.id}}/picture">
					</div>
				</div>
				{{#icon}}<br/><img class="float fb" src="{{icon}}">{{/icon}}
			</div>
			<div class="elgg-body">
				<ul class="elgg-menu elgg-menu-river elgg-menu-hz elgg-menu-river-default">
					{{#can_like}}
					<li class="elgg-menu-item-like">
						<a href="#" class="gwfb tooltip s" title="<?php echo elgg_echo('deck_river:facebook:action:like'); ?>"><span class="elgg-icon elgg-icon-thumbs-up"></span></a>
					</li>
					<li class="elgg-menu-item-retweet pls prm">
						<a href="#" class="gwfb tooltip s" title="<?php echo elgg_echo('deck_river:facebook:action:share'); ?>"><span class="elgg-icon elgg-icon-retweet"></span></a>
					</li>
					{{/can_like}}
					{{{menu.default}}}
					{{#submenu}}
					<li class="elgg-submenu">
						<span class="elgg-icon elgg-icon-hover-menu link gwf"></span>
						<ul class="elgg-module-popup hidden">
							<li class="elgg-menu-item-{{name}}"><a href="#"><span class="elgg-icon elgg-icon-{{name}}"></span>{{{content}}}</a>
						</ul>
					</li>
					{{/submenu}}
				</ul>
				<div class="elgg-river-summary prl">
					<span class="facebook-{{#from.category}}page{{/from.category}}{{^from.category}}user{{/from.category}}-info-popup info-popup" title="{{from.id}}">{{from.name}}</span>{{#summary}}&nbsp;{{{summary}}}{{/summary}}{{#via}}&nbsp;<?php echo elgg_echo('deck_river:via'); ?>&nbsp;<a class="facebook-{{#category}}page{{/category}}{{^category}}user{{/category}}-info-popup info-popup link" title="{{id}}" href="#">{{name}}</a>{{/via}}
					{{#properties}}
						<?php echo elgg_echo('river:facebook:photo:shared_story'); ?>&nbsp;<a target="_blank" href="{{link}}"><?php echo elgg_echo('river:facebook:photo:shared_story:photo'); ?></a>&nbsp;<?php echo elgg_echo('river:facebook:photo:shared_story:of'); ?>&nbsp;<a target="_blank" href="{{href}}">{{text}}</a>
					{{/properties}}
					<br><span class="elgg-river-timestamp">
						<a href="https://facebook.com/{{id}}" target="_blank">
							<span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{created_time}}" time="{{posted}}">{{friendly_time}}</acronym>
							</span>
						</a>
					</span>
				</div>
				<div class="elgg-river-message main" data-message_original="{{message_original}}">{{{message}}}{{#showOnFacebook}}&nbsp;<a target="_blank" href="https://facebook.com/{{id}}">{{showOnFacebook}}</a>{{/showOnFacebook}}</div>
				{{#link}}
				{{#typevideo}}
				<a class="elgg-river-responses linkbox-droppable media-video-popup" href="{{link}}" data-source="{{source}}">
				{{/typevideo}}
				{{^typevideo}}
				<a class="elgg-river-responses {{^typephoto}}linkbox-droppable{{/typephoto}}" target="_blank" href="{{link}}">
				{{/typevideo}}
					<div class="elgg-river-image" data-mainimage="{{full_picture}}" data-title="{{name}}" data-url="{{link}}" data-description="{{description}}" data-editable="true">
						{{#full_picture}}
						<div id="img{{id}}" class="elgg-image float gwfb" style="background-image: url({{full_picture}});"></div>
						{{/full_picture}}
						{{^typephoto}}
						<div class="elgg-body">
							{{#name}}<h4>{{name}}</h4>{{/name}}
							{{#caption}}<span class="elgg-subtext">{{caption}}</span>{{/caption}}
							{{#description}}<div>{{{description}}}</div>{{/description}}
						</div>
						{{/typephoto}}
					</div>
				</a>
				{{/link}}
				<div class="elgg-river-responses sharelike-count">
					{{#likes}}<span class="elgg-icon elgg-icon-thumbs-up-alt float pts gwfb prs{{#liked}} liked{{/liked}}"></span><span class="float likes-popup prm pts" data-users="{{likes.users}}">{{likes.string}}</span>{{/likes}}
					{{#shares}}<span class="elgg-icon elgg-icon-retweet-sub float pts gwfb"></span><span class="float shares-popup prm pts">&nbsp;{{shares.string}}</span>{{/shares}}
				</div>
				{{#comments}}
					{{#comments.before}}
						<ul class="elgg-list elgg-river-comments">
							<li><a rel="toggle" href="#comment-part-{{id}}-{{rand}}">{{comments.before}}</a></li>
						</ul>
						<ul id="comment-part-{{id}}-{{rand}}" class="elgg-list elgg-river-comments hidden">
							{{#comments.dataBefore}}
								{{> erFBt-comment}}
							{{/comments.dataBefore}}
						</ul>
					{{/comments.before}}
					<ul class="elgg-list elgg-river-comments elgg-list-comments">
						{{#comments.data}}
							{{> erFBt-comment}}
						{{/comments.data}}
					</ul>
				{{/comments}}
				{{^comments}}
				<ul class="elgg-list elgg-river-comments elgg-list-comments"></ul>
				{{/comments}}
				{{#can_comment}}
				<ul class="elgg-list elgg-river-comments pts">
					<span class="elgg-icon elgg-icon-speech-bubble-alt float gwfb prs"></span><a href="#comment-form-{{id}}-{{rand}}" class="prm" rel="toggle"><?php echo elgg_echo('deck_river:facebook:action:comment'); ?></a>
					<div id="comment-form-{{id}}-{{rand}}" class="facebook-comment-form hidden">
						<textarea class="comment"></textarea>
						<a href="#" class="elgg-button elgg-button-submit">Commenter</a>
					</div>
				</ul>
				{{/can_comment}}
			</div>
		</div>
</li></script>



<!-- Templates for elgg river facebook comment item -->
<script id="erFBt-comment" type="text/template">
	<li class="elgg-item" id="{{id}}">
		<div class="elgg-image-block clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="facebook-{{#from.category}}page{{/from.category}}{{^from.category}}user{{/from.category}}-info-popup info-popup" title="{{from.id}}">
						<img title="{{from.name}}" alt="{{from.name}}" src="http://graph.facebook.com/{{from.id}}/picture?width=24&height=24" width="24" height="24">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				<div class="elgg-river-summary prl">
					<span class="facebook-{{#from.category}}page{{/from.category}}{{^from.category}}user{{/from.category}}-info-popup info-popup" title="{{from.id}}">{{from.name}}</span>
					<span class="elgg-river-timestamp">
						<a target="_blank" href="https://facebook.com/{{from.id}}/status/{{id}}" target="_blank">
							<br><span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{created_time}}" time="{{posted}}">{{friendly_time}}</acronym>
							</span>
						</a>&nbsp;&bull;<a class="comment-item-like phs{{#user_likes}} unlike{{/user_likes}}" href="#">{{like}}</a>{{#like_count}}<span>&bull;</span><span class="elgg-icon elgg-icon-thumbs-up{{#user_likes}} liked{{/user_likes}}"></span><span class="counter">{{like_count}}</span>{{/like_count}}
					</span>
				</div>
				<div class="elgg-river-message">{{{message}}}</div>
			</div>
		</div>
	</li>
</script>



<!-- Template for Facebook user profile popup -->
<script id="facebook-user-profile-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected"><a href="#fb-{{uid}}-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
		<li><a href="#fb-{{uid}}-feed"><?php echo elgg_echo('activity'); ?></a></li>
	</ul>
	<ul class="elgg-body">
		<li id="fb-{{uid}}-info-profile">
			<div class="elgg-avatar elgg-avatar-large float prm">
				<a href="http://www.facebook.com/{{username}}" title="{{first_name}} {{last_name}}" rel="nofollow" target="_blank">
					<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
					<div class="avatar-wrapper center">
						<img width="200px" title="{{first_name}} {{last_name}}" alt="{{first_name}} {{last_name}}" src="https://graph.facebook.com/{{uid}}/picture?width=200&height=200">
					</div>
				</a>
			</div>
			<div class="plm">
				<h1 class="pts mbm">{{first_name}} {{last_name}}</h1>
				<h2 class="mbs" style="font-weight:normal;">{{username}}</h2>
				<div>{{{quotes}}}</div>
			</div>
			<div id="profile-details" class="elgg-body pll">
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{friend_count}}</div><?php echo elgg_echo('deck_river:facebook:count:friends'); ?></li>
					{{#mutual_friend_count}}<li><div class="stats">{{mutual_friend_count}}</div><?php echo elgg_echo('deck_river:facebook:count:mutual_friends'); ?></li>{{/mutual_friend_count}}
					<li><div class="stats">{{subscriber_count}}</div><?php echo elgg_echo('deck_river:facebook:count:subscribers'); ?></li>
				</ul>
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{likes_count}}</div><?php echo elgg_echo('deck_river:facebook:count:likes'); ?></li>
					<li><div class="stats">{{wall_count}}</div><?php echo elgg_echo('deck_river:facebook:count:walls'); ?></li>
					<li><div class="stats">{{notes_count}}</div><?php echo elgg_echo('deck_river:facebook:count:notes'); ?></li>
				</ul>
				<div class="even">
					<b><?php echo elgg_echo('Facebook'); ?> :</b> <a class="external" target="_blank" href="{{profile_url}}">{{profile_url}}</a>
				</div>
				{{#website}}
				<div class="even">
					<b><?php echo elgg_echo('site'); ?> :</b> {{{website}}}
				</div>
				{{/website}}
				{{#locale}}
				<div class="even">
					<b><?php echo elgg_echo('user:set:language'); ?> :</b> {{locale}}
				</div>
				{{/locale}}
				{{#books}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:books'); ?> :</b> {{books}}
				</div>
				{{/books}}
				{{#music}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:music'); ?> :</b> {{music}}
				</div>
				{{/music}}
				{{#tv}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:tv'); ?> :</b> {{tv}}
				</div>
				{{/tv}}
				{{#movies}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:movies'); ?> :</b> {{movies}}
				</div>
				{{/movies}}
				{{#profile_update_time}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:profile_update_time'); ?> :</b> {{{profile_update_time}}}
				</div>
				{{/profile_update_time}}
			</div>
		</li>
		<li id="fb-{{uid}}-feed" class="column-river hidden" >
			<ul class="column-header hidden" data-network="facebook" data-query="{{uid}}/feed" data-token="{{token}}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>



<!-- Template for Facebook page profile popup -->
<script id="facebook-page-profile-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected"><a href="#fb-{{id}}-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
		<li><a href="#fb-{{id}}-feed"><?php echo elgg_echo('activity'); ?></a></li>
	</ul>
	<ul class="elgg-body">
		<li id="fb-{{id}}-info-profile">
			<div class="cover-block">
				{{#cover.source}}
				<div class="cover-wrapper center pbs">
					<span><img src="{{cover.source}}"></span>
				</div>
				{{/cover.source}}
				<div class="elgg-avatar elgg-avatar-large float prm">
					<a href="{{link}}" title="{{name}}" rel="nofollow" target="_blank">
						<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
						<div class="avatar-wrapper center">
							<img width="200px" title="{{name}}" alt="{{name}}" src="https://graph.facebook.com/{{id}}/picture?width=100&height=100">
						</div>
					</a>
				</div>
				<div class="plm">
					<h1 class="pts mbm">{{name}}</h1>
				</div>
			</div>
			<div id="profile-details" class="elgg-body pll">
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{likes}}</div><?php echo elgg_echo('deck_river:facebook:count:likes'); ?></li>
					<li><div class="stats">{{talking_about_count}}</div><?php echo elgg_echo('deck_river:facebook:count:talking_about'); ?></li>
					{{#checkins}}<li><div class="stats">{{checkins}}</div><?php echo elgg_echo('deck_river:facebook:count:checkins'); ?></li>{{/checkins}}
				</ul>
				{{#about}}
				<div class="even">
					<b><?php echo elgg_echo('about'); ?> :</b><p style="white-space:pre-line;">{{{about}}}</p>
				</div>
				{{/about}}
				<div class="even">
					<b><?php echo elgg_echo('Facebook'); ?> :</b> <a class="external" target="_blank" href="{{link}}">{{link}}</a>
				</div>
				{{#website}}
				<div class="even">
					<b><?php echo elgg_echo('site'); ?> :</b> {{{website}}}
				</div>
				{{/website}}
				{{#category_list}}
				<div class="even">
					<b><?php echo elgg_echo('deck_river:facebook:category_list'); ?> :</b> {{{name}}}
				</div>
				{{/category_list}}
				{{#phone}}
				<div class="even">
					<b><?php echo elgg_echo('phone'); ?> :</b> {{{phone}}}
				</div>
				{{/phone}}
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
				{{#updated_time}}
				<div class="even">
					<b><?php echo elgg_echo('usersettings:statistics:label:lastaction'); ?> :</b> {{updated_time}}
				</div>
				{{/updated_time}}
			</div>
		</li>
		<li id="fb-{{id}}-feed" class="column-river hidden" >
			<ul class="column-header hidden" data-network="facebook" data-query="{{id}}/feed" data-token="{{token}}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>


