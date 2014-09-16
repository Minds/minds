<!-- Templates for elgg river linkedin item -->
<script id="elgg-river-linkedin-template" type="text/template"><li class="elgg-list-item item-linkedin item-linkedin-{{updateKey}}"
		data-username="{{updateContent.person.id}}"
		data-id="{{updateKey}}"
		>
		<div class="elgg-image-block elgg-river-item clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="linkedin-user-info-popup info-popup link" title="{{updateContent.person.id}}">
						<img title="{{updateContent.person.firstName}} {{updateContent.person.lastName}}" alt="{{updateContent.person.id}}" src="{{updateContent.person.pictureUrl}}">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				<ul class="elgg-menu elgg-menu-river elgg-menu-hz elgg-menu-river-default">
					{{#isLikable}}
					<li class="elgg-menu-item-linkedin-like">
						<a href="#" title="<?php echo elgg_echo('like'); ?>" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-thumbs-up-alt"></span></a>
					</li>
					<li class="elgg-menu-item-retweet prm">
						<a href="#" title="<?php echo elgg_echo('share'); ?>" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-retweet"></span></a>
					</li>
					{{/isLikable}}
				</ul>
				<div class="elgg-river-summary prl">
					<span class="linkedin-user-info-popup info-popup" title="{{updateContent.person.id}}">{{updateContent.person.firstName}} {{updateContent.person.lastName}}</span> {{#summary}}{{summary}}{{/summary}}<br/>
					<span class="elgg-river-timestamp">
						<span class="elgg-friendlytime">
							<acronym class="tooltip w" title="{{posted}}" time="{{timestamp}}">{{friendly_time}}</acronym>
						</span>
					</span>
				</div>
				{{#typeCONN}}
				<div class="elgg-image-block clearfix mts">
					<div class="linkedin-user-info-popup info-popup link" title="{{id}}">
						<div class="elgg-image">
							<img src="{{img}}" style="height: 32px;" />
						</div>
					</div>
					<div class="elgg-body">
						<h4><div class="linkedin-user-info-popup info-popup link" title="{{id}}">{{name}}</div></h4>
						<span class="elgg-subtext">{{headline}}</span>
						{{#description}}<div>{{{description}}}</div>{{/description}}
					</div>
				</div>
				{{/typeCONN}}
				{{#typeSTAT}}
				<div class="elgg-river-message">{{{status}}}</div>
				{{/typeSTAT}}
				{{#typeSHAR}}
				{{#status}}<div class="elgg-river-message">{{{status}}}</div>{{/status}}
				<a class="elgg-river-responses linkbox-droppable mbs" target="_blank" href="{{metadatas.submittedUrl}}">
					<div class="elgg-river-image mvs" data-mainimage="{{metadatas.thumbnailUrl}}" data-title="{{metadatas.title}}" data-url="{{metadatas.submittedUrl}}" data-description="{{metadatas.description}}" data-editable="true">
						<img src="{{metadatas.thumbnailUrl}}"/>
						<div class="elgg-body">
							{{#metadatas.title}}<h4>{{metadatas.title}}</h4>{{/metadatas.title}}
							{{#metadatas.description}}<div>{{{metadatas.description}}}</div>{{/metadatas.description}}
						</div>
					</div>
				</a>
				{{/typeSHAR}}
				{{#typeGroupPost}}
				{{#title}}<h3 class="mts">{{title}}</h3>{{/title}}
				{{#status}}<div class="elgg-river-message">{{{status}}}</div>{{/status}}
				<a class="elgg-river-responses linkbox-droppable mbs" target="_blank" href="{{attachment.contentUrl}}">
					<div class="elgg-river-image mvs" data-mainimage="{{attachment.imageUrl}}" data-title="{{attachment.title}}" data-url="{{attachment.contentUrl}}" data-description="{{attachment.summary}}" data-editable="true">
						<img src="{{attachment.imageUrl}}"/>
						<div class="elgg-body">
							{{#attachment.title}}<h4>{{attachment.title}}</h4>{{/attachment.title}}
							{{#attachment.summary}}<div>{{{attachment.summary}}}</div>{{/attachment.summary}}
						</div>
					</div>
				</a>
				{{/typeGroupPost}}
				<div class="elgg-river-responses">
					{{#numLikes}}
						<span class="elgg-icon elgg-icon-star-sub float gwfb{{#isLiked}} favorited{{/isLiked}}"></span>
						<span class="phs float">{{{numLikes}}}</span>
					{{/numLikes}}
				</div>
				{{#isCommentable}}
				<ul class="elgg-list elgg-river-comments elgg-list-comments">
					{{#updateComments.values}}
					<li class="elgg-item" id="{{id}}">
						<div class="elgg-image-block clearfix">
							<div class="elgg-image">
								<div class="elgg-avatar elgg-avatar-small">
									<div class="linkedin-user-info-popup info-popup" title="{{person.id}}">
										<img title="{{person.firstName}} {{person.lastName}}" alt="{{person.firstName}} {{person.lastName}}" src="{{person.pictureUrl}}" width="24" height="24">
									</div>
								</div>
							</div>
							<div class="elgg-body">
								<div class="elgg-river-summary prl">
									<span class="linkedin-user-info-popup info-popup" title="{{person.id}}">{{person.firstName}} {{person.lastName}}</span>
									<span class="elgg-river-timestamp">
										<span class="elgg-friendlytime">
											<acronym class="tooltip w" title="{{created_time}}" time="{{timestamp}}">{{friendly_time}}</acronym>
										</span>
									</span>
								</div>
								<div class="elgg-river-message">{{{comment}}}</div>
							</div>
						</div>
					</li>
					{{/updateComments.values}}
				</ul>
				<ul class="elgg-list elgg-river-comments pts">
					<span class="elgg-icon elgg-icon-speech-bubble-alt float gwfb prs"></span><a href="#comment-form-{{updateKey}}" class="prm" rel="toggle"><?php echo elgg_echo('deck_river:facebook:action:comment'); ?></a>
					<div id="comment-form-{{updateKey}}" class="hidden linkedin-comment-form">
						<textarea class="comment"></textarea>
						<a href="#" class="elgg-button elgg-button-submit">Commenter</a>
					</div>
				</ul>
				{{/isCommentable}}
			</div>
		</div>
</li></script>



<!-- Template for linkedin user profile popup -->
<script id="linkedin-user-profile-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li class="elgg-state-selected"><a href="#{{id}}-info-profile"><?php echo elgg_echo('profile'); ?></a></li>
		<li><a href="#{{id}}-updates"><?php echo elgg_echo('posts'); ?></a></li>
	</ul>
	<ul class="elgg-body">
		<li id="{{id}}-info-profile">
			<div class="elgg-avatar elgg-avatar-large float prm">
				<a href="{{publicProfileUrl}}" title="{{id}}" rel="nofollow" target="_blank">
					<span class="gwfb hidden"><br><?php echo elgg_echo('deck_river:go_to_profile'); ?></span>
					<div class="avatar-wrapper center">
						<img width="200px" title="{{id}}" alt="{{firstName}} {{lastName}}" src="{{pictureUrl}}">
					</div>
				</a>
			</div>
			<div class="plm">
				<h1 class="pts mbs">{{firstName}} {{lastName}}</h1>
				<div>{{{headline}}}</div>
			</div>
			<div id="profile-details" class="elgg-body pll">
				<ul class="user-stats mbs pts">
					<li><div class="stats">{{numConnections}}{{#numConnectionsCapped}}+{{/numConnectionsCapped}}</div><?php echo elgg_echo('linkedin:connexions'); ?></li>
					<li><div class="stats">{{distance}}</div><?php echo elgg_echo('linkedin:distance'); ?></li>
				</ul>
				<div class="even">
					<b><?php echo elgg_echo('linkedin'); ?> :</b> <a class="external" target="_blank" href="{{publicProfileUrl}}">{{publicProfileUrl}}</a>
				</div>
				{{#summary}}
				<div class="even">
					<b><?php echo elgg_echo('linkedin:summary'); ?> :</b> {{{summary}}}
				</div>
				{{/summary}}
			</div>
		</li>
		<li id="{{id}}-updates" class="column-river hidden" >
			<ul class="column-header hidden" data-network="linkedin" data-river_type="linkedin_OAuth" data-params="{&quot;method&quot;: &quot;blog/{{screen_name}}.linkedin.com/posts&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>
