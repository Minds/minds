<!-- Template for linkbox -->
<script id="linkbox-template" type="text/template">
	<div class="elgg-image-block clearfix">
		{{#mainimage}}
		<ul class="elgg-image">
			<div class="link_picture image-wrapper center tooltip sw t25 gwfb" title="<?php echo elgg_echo('deck_river:linkbox:hidepicture'); ?>">
				<img height="80px" src="{{mainimage}}">
			</div>
			{{#images}}
				<li class="image-wrapper center t25"><img height="80px" src="{{src}}"></li>
			{{/images}}
		</ul>
		{{/mainimage}}
		<div class="elgg-body pts">
			<ul class="elgg-menu elgg-menu-entity elgg-menu-hz float-alt">
				<span class="elgg-icon elgg-icon-delete link"></span>
			</ul>
			<div class="">
				<h4 class="link_name pas mrl" {{#editable}}contenteditable="true"{{/editable}}>{{title}}</h4>
				{{#url}}
				<div class="elgg-subtext pls">
					{{url}}
				</div>
				{{/url}}
				<input type="hidden" name="link_url" value="{{url}}">
				<div class="link_description pas" {{#editable}}contenteditable="true"{{/editable}}>{{description}}</div>
			</div>
		</div>
	</div>
</script>