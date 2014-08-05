<?php 
$user= elgg_get_logged_in_user_entity();
?>
<!-- Templates deck_river -->


<!-- Pass var from php to client -->
<script type="text/javascript">
	var deckRiverSettings = <?php echo elgg_is_logged_in() ? $user->getPrivateSetting('deck_river_settings')?:'null' : 'null'; ?>;
	var FBappID = <?php $FBappID = elgg_get_plugin_setting('facebook_app_id', 'elgg-deck_river'); echo json_encode($FBappID ? $FBappID : 'null'); ?>;
	var FBdefaultFields = "actions,caption,created_time,from,link,message,story,story_tags,id,full_picture,icon,name,object_id,parent_id,type,with_tags,description,shares,via,feed_targeting,to,source,properties,subscribed,updated_time,picture,is_published,privacy,status_type,targeting,timeline_visibility,comments.fields(parent,id,like_count,message,created_time,from,attachment,can_comment,can_remove,comment_count,message_tags,user_likes),likes.fields(username)";
	var site_shorturl = <?php $site_shorturl = elgg_get_plugin_setting('site_shorturl', 'elgg-deck_river'); echo json_encode($site_shorturl ? $site_shorturl : false); ?>;
	var deck_river_min_width_column = <?php $mwc = elgg_get_plugin_setting('min_width_column', 'elgg-deck_river'); echo $mwc ? $mwc : 300; ?>;
	var deck_river_max_nbr_columns = <?php $mnc = elgg_get_plugin_setting('max_nbr_column', 'elgg-deck_river');  echo $mnc ? $mnc : 10; ?>;
</script>



<!-- Template for column -->
<script id="column-template" type="text/template">
	<li class="column-river" id="{{column}}">
		<ul class="column-header"></ul>
		<ul class="column-filter"></ul>
		<ul class="elgg-river elgg-list">
			<div class="elgg-ajax-loader"></div>
		</ul>
		<div class="river-to-top hidden link t25 gwfb pas"></div>
	</li>
</script>



<!-- Template for share menu -->
<script id="share-menu" type="text/template">
	<ul class="elgg-module-popup share-menu elgg-submenu">
		{{#logged_in}}
		<li>
			<a href="#" onclick="javascript:elgg.thewire.insertInThewire('{{sl}}');">
				<?php echo elgg_echo('thewire:put_shortlink_in_wire'); ?>
			</a>
		</li>
		<li>
			<a href="#" onclick="javascript:elgg.thewire.insertInThewire('{{{text}}} {{sl}}');">
				<?php echo elgg_echo('thewire:put_title_shortlink_in_wire'); ?>
			</a>
		</li>
		{{/logged_in}}
		<li{{#logged_in}} class="section"{{/logged_in}}>
			<a href="#" onclick="javascript:(function(){var w=671,h=216,x=Number((window.screen.width-w)/2),y=Number((window.screen.height-h)/2),d=window,u='http://facebook.com/share.php?u={{sl}}';a=function(){d.open(u,'f','scrollbars=0,toolbar=0,location=0,resizable=0,status=0,width='+w+',height='+h+',left='+x+',top='+y)};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else a();void(0);})()">
				<span class="elgg-icon facebook-icon"></span>&nbsp;<?php echo elgg_echo('share:on'); ?>&nbsp;Facebook
			</a>
		</li>
		<li>
			<a href="#" onclick="javascript:(function(){var w=671,h=285,x=Number((window.screen.width-w)/2),y=Number((window.screen.height-h)/2),d=window,u='http://twitter.com/home?status={{text}} {{sl}}';a=function(){d.open(u,'t','scrollbars=0,toolbar=0,location=0,resizable=0,status=0,width='+w+',height='+h+',left='+x+',top='+y)};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else a();void(0);})()">
				<span class="elgg-icon twitter-icon"></span>&nbsp;<?php echo elgg_echo('share:on'); ?>&nbsp;Twitter
			</a>
		</li>
		<li>
			<a href="#" onclick="javascript:(function(){var w=600,h=200,x=Number((window.screen.width-w)/2),y=Number((window.screen.height-h)/2),d=window,u='https://plus.google.com/share?url={{sl}}';a=function(){d.open(u,'g','scrollbars=0,toolbar=0,location=0,resizable=0,status=0,width='+w+',height='+h+',left='+x+',top='+y)};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else a();void(0);})()">
				<span class="elgg-icon google-icon"></span>&nbsp;<?php echo elgg_echo('share:on'); ?>&nbsp;Google+
			</a>
		</li>
	</ul>
</script>



<!-- Template for popups -->
<script id="popup-template" type="text/template">
	<div id="{{popupID}}" class="elgg-module-popup deck-popup ui-draggable" style="position: relative; z-index: 100;">
		<div class="elgg-head">
			<h3>{{popupTitle}}</h3>
			<a href="#" class="pin">
				<span class="elgg-icon elgg-icon-push-pin tooltip s" title="<?php echo htmlspecialchars(elgg_echo('deck-river:popups:pin')); ?>"></span>
			</a>
			<a href="#">
				<span class="elgg-icon elgg-icon-delete-alt tooltip s" title="<?php echo elgg_echo('deck-river:popups:close'); ?>"></span>
			</a>
		</div>
		<div class="elgg-body">
			<div class="elgg-ajax-loader"></div>
		</div>
	</div>
</script>



<!-- Template for hashtag popup -->
<script id="hashtag-popup-template" type="text/template">
	<ul class="elgg-tabs elgg-htabs">
		<li><a class="elgg" href="#{{hashtag}}-elgg"><?php echo elgg_get_site_entity()->name; ?></a></li>
		<li><a class="twitter" href="#{{hashtag}}-twitter">Twitter</a></li>
	</ul>
	<ul class="elgg-body">
		<li id="{{hashtag}}-elgg" class="column-river hidden">
			<ul class="column-header hidden" data-network="elgg" data-river_type="entity_river" data-entity="#{{hashtag}}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
		<li id="{{hashtag}}-twitter" class="column-river hidden">
			<ul class="column-header hidden" data-network="twitter" data-river_type="twitter_OAuth" data-params="{&quot;method&quot;: &quot;get_searchTweets&quot;, &quot;q&quot;: &quot;%23{{hashtag}}&quot;, &quot;count&quot;: &quot;100&quot;, &quot;include_entities&quot;: &quot;1&quot;}"></ul>
			<ul class="elgg-river elgg-list"><div class="elgg-ajax-loader"></div></ul>
		</li>
	</ul>
</script>



<!-- Templates for elgg river item -->
<script id="elgg-river-template" type="text/template"><li class="elgg-list-item item-elgg-{{id}} {{type}} {{subtype}} {{action_type}}"
	data-access_id="{{access_id}}"
	data-annotation_id="{{annotation_id}}"
	data-id="{{id}}"
	data-object_guid="{{object_guid}}"
	data-subject_guid="{{subject_guid}}"
	data-username="{{user.username}}"
	data-timeid="{{posted}}"
	data-text="{{text}}">
	<div class="elgg-image-block elgg-river-item clearfix">
		<div class="elgg-image">
			<div class="elgg-avatar elgg-avatar-small">
				<div class="elgg-user-info-popup info-popup" title="{{user.username}}">
					<img title="{{user.username}}" alt="{{user.username}}" src="{{user.icon}}">
				</div>
			</div>
		</div>
		<div class="elgg-body">
			<ul class="elgg-menu elgg-menu-river elgg-menu-hz elgg-menu-river-default">
			{{#menu}}
			{{^sub}}
			<li class="elgg-menu-item-{{name}} prs"><a href="#" title="{{title}}" class="gwfb tooltip s"><span class="elgg-icon elgg-icon-{{name}}"></span></a></li>
			{{/sub}}
			{{#sub}}
			<li class="elgg-submenu">
				<span class="elgg-icon elgg-icon-hover-menu link gwf"></span>
				<ul class="elgg-module-popup hidden">
					{{#childs}}<li class="elgg-menu-item-{{name}}">{{{content}}}</li>{{/childs}}
				</ul>
			</li>
			{{/sub}}
			{{/menu}}
			</ul>
			<div class="elgg-river-summary prl">
				{{{summary}}}<br/>
				<span class="elgg-river-timestamp">
					<span class="elgg-friendlytime">
						<acronym class="tooltip w" title="{{posted_acronym}}" time="{{posted}}">{{friendly_time}}</acronym>
					</span>
					{{#method}}<?php echo elgg_echo('deck_river:via'); ?>&nbsp;{{{method}}}{{/method}}
				</span>
			</div>
			<div class="elgg-river-message">{{{message}}}</div>
			{{#responses}}
			<div class="elgg-river-responses">
				<div class="response-loader float hidden"></div>
				<span class="elgg-icon elgg-icon-speech-bubble-alt float gwfb prs"></span>
				<a href="#" class="thread float" data-thread="{{responses}}" data-network="elgg">
					<?php echo elgg_echo('deck_river:thread'); ?>
				</a>
			</div>
			{{/responses}}
		</div>
	</div>
</li></script>

