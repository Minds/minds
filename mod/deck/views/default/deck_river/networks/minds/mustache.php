<!-- Templates for minds feed -->
<script id="elgg-river-minds-template" type="text/template"><li class="elgg-list-item item-minds-{{id}}"
		data-username="{{subjectObj.username}}"
		data-id="{{id}}"
		data-object_guid="{{objectObj.guid}}"
		data-text="{{objectObj.description}}"
		>
		<div class="elgg-image-block minds-river-header clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class="minds-user-info-popup info-popup" title="{{subjectObj.name}}">
						<img title="{{subjectObj.name}}" alt="{{subjectObj.name}}" src="{{subjectObj.avatar_url}}">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				<div class="elgg-minds-summary prl">
					<span class="minds-user-info-popup info-popup" title="{{subjectObj.name}}">{{subjectObj.name}}</span><br/>
					<span class="elgg-river-timestamp">
						<a href="{{subjectObj.url}}" target="_blank">
							<span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{posted}}" time="{{posted}}">{{friendly_time}}</acronym>
							</span>
						</a>
					</span>
				</div>
			</div>
		</div>

		<div class="minds-river-message">
			{{body}} 
			{{objectObj.excerpt}} 
			{{#objectObj.icon}}<img src="{{objectObj.icon}}"/>{{/objectObj.icon}}
		</div>
				
		{{#attachment_guid}}
		<div class="minds-river-attachments">
			<img src="{{attachment_url}}"/>
		</div>
		{{/attachment_guid}}
		<div class="minds-river-responses">
		
				<ul class="elgg-list elgg-river-comments elgg-list-comments">
					{{#comments}}
						{{> elgg-river-minds-template-comments}}
					{{/comments}}
				</ul>
		</div>
</li>
</script>

<!-- Templates for elgg river facebook comment item -->
<script id="elgg-river-minds-template-comments" type="text/template">
	<li class="elgg-item" id="{{id}}">
		<div class="elgg-image-block clearfix">
			<div class="elgg-image">
				<div class="elgg-avatar elgg-avatar-small">
					<div class=" info-popup" title="{{owner.guid}}">
						<img title="{{owner.name}}" alt="{{owner.name}}" src="{{owner.avatar_url}}" width="24" height="24">
					</div>
				</div>
			</div>
			<div class="elgg-body">
				<div class="elgg-river-summary prl">
					<span class="facebook-{{#from.category}}page{{/from.category}}{{^from.category}}user{{/from.category}}-info-popup info-popup" title="{{owner.id}}">{{owner.name}}</span>
					<span class="elgg-river-timestamp">
						<a target="_blank" href="https://facebook.com/{{from.id}}/status/{{id}}" target="_blank">
							<br><span class="elgg-friendlytime">
								<acronym class="tooltip w" title="{{time_created}}" time="{{posted}}">{{friendly_time}}</acronym>
							</span>
						</a>
					</span>
				</div>
				<div class="elgg-river-message">{{{description}}}</div>
			</div>
		</div>
	</li>
</script>