<!-- Templates for elgg river tumblr item -->
<script id="elgg-river-tumblr-template" type="text/template"><li class="elgg-list-item item-tumblr-{{id}}"
		data-timeid="{{id}}"
		data-username="{{blog_name}}"
		data-id="{{id}}"
		data-object_guid="{{id}}"
		data-reblog_key="{{reblog_key}}"
		>
		<div class="elgg-image-block elgg-river-item clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="tumblr-user-info-popup info-popup" title="{{blog_name}}">
						<img title="{{blog_name}}" alt="{{blog_name}}" src="http://api.tumblr.com/v2/blog/{{blog_name}}.tumblr.com/avatar/48">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				<ul class="elgg-menu elgg-menu-river elgg-menu-hz elgg-menu-river-default">
					<li class="elgg-menu-item-tumblr-like">
						<a href="#" title="<?php echo elgg_echo('like'); ?>" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-thumbs-up-alt"></span></a>
					</li>
					<li class="elgg-menu-item-retweet prm">
						<a href="#" title="<?php echo elgg_echo('share'); ?>" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-retweet"></span></a>
					</li>
				</ul>
				<div class="elgg-river-summary prl">
					<span class="tumblr-user-info-popup info-popup" title="{{blog_name}}">{{blog_name}}</span><br/>
					<span class="elgg-river-timestamp">
						<a href="{{post_url}}" target="_blank">
							<span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{created_at}}" time="{{timestamp}}">{{friendly_time}}</acronym>
							</span>
						</a>
					</span>
				</div>
				{{#typetext}}
				<div class="elgg-river-message">{{{body}}}</div>
				<div class="elgg-river-image hidden" data-text="{{body}}" data-editable="true"></div>
				{{/typetext}}
				{{#typephoto}}
					{{#photos}}
					<br><img id="img{{img_id}}" src="{{original_size.url}}"/>
					{{/photos}}
					<div class="elgg-river-message">{{{caption}}}</div>
					<div class="elgg-river-image hidden" data-text="{{caption}}" data-editable="true"></div>
				{{/typephoto}}
				{{#typelink}}
					<h3 class="mts"><a target="_blank" href="{{url}}">{{title}}</a></h3>
					<div class="elgg-river-message">{{{description}}}</div>
					<div class="elgg-river-image hidden" data-title="{{title}}" data-url="{{url}}" data-text="{{description}}" data-editable="true"></div>
				{{/typelink}}
				{{#typevideo}}
				<a class="elgg-river-responses linkbox-droppable media-video-popup" href="{{link}}" data-source="{{source}}">
					<div class="elgg-river-image" data-mainimage="{{full_picture}}" data-title="{{name}}" data-url="{{link}}" data-description="{{description}}" data-editable="true">
						{{#full_picture}}
						<img src="{{full_picture}}"/>
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
				{{/typevideo}}
				{{#tags}}
				<span class="elgg-subtext">#{{.}}</span>&nbsp;
				{{/tags}}
				<div class="elgg-river-responses">
					{{#note_count}}
						<span class="elgg-icon elgg-icon-star-sub float gwfb{{#liked}} favorited{{/liked}}"></span>
						<span class="phs float">{{{note_count}}}</span>
					{{/note_count}}
				</div>
			</div>
		</div>
</li></script>


<!-- Template for tumblr user profile popup -->
<script id="tumblr-user-profile-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected"><a href="#{{id}}-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
		<li><a href="#{{id}}-blog/{{screen_name}}.tumblr.com/posts"><?php echo elgg_echo('posts'); ?></a></li>
	</ul>
	<ul class="elgg-body">
		<li id="{{id}}-info-profile">
			<div class="elgg-avatar elgg-avatar-large float prm">
				<a href="http://tumblr.com/{{screen_name}}" title="{{screen_name}}" rel="nofollow" target="_blank">
					<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
					<div class="avatar-wrapper center">
						<img width="200px" title="{{screen_name}}" alt="{{screen_name}}" src="{{profile_image_url}}">
					</div>
				</a>
			</div>
			<div class="plm">
				<h1 class="pts mbs">{{name}}</h1>
				<h2><a href="#" class="tumblr-user-info-popup info-popup mbs" style="font-weight:normal;" title="{{screen_name}}">@{{screen_name}}</a></h2>
				<div>{{{description}}}</div>
			</div>
			<div id="profile-details" class="elgg-body pll">
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{posts}}</div><?php echo elgg_echo('tumblr:posts'); ?></li>
					<li><div class="stats">{{updated}}</div><?php echo elgg_echo('tumblr:updated'); ?></li>
					<li><div class="stats">{{likes}}</div><?php echo elgg_echo('likes'); ?></li>
				</ul>
				<div class="even">
					<b><?php echo elgg_echo('tumblr'); ?> :</b> <a class="external" target="_blank" href="http://tumblr.com/{{screen_name}}">http://tumblr.com/{{screen_name}}</a>
				</div>
			</div>
		</li>
		<li id="{{id}}-blog/{{screen_name}}.tumblr.com/posts" class="column-river hidden" >
			<ul class="column-header hidden" data-network="tumblr" data-river_type="tumblr_OAuth" data-params="{&quot;method&quot;: &quot;blog/{{screen_name}}.tumblr.com/posts&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>
