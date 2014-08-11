<?php if (0): ?><script><?php endif; ?>

// Get url of networks to authorize user
$(document).on('click', '#authorize-linkedin', function(){
	window.open(elgg.get_site_url() + 'authorize/linkedin', 'ConnectWithOAuth', 'location=0,status=0,width=800,height=400');

	return false;
});


/**
 * LinkedIn Display *
 * @param {array}	json response
 */
elgg.deck_river.linkedinDisplayItems = function(response) {
	var output = '',
		imgs = [],
		linkedinRiverTemplate = Mustache.compile($('#elgg-river-linkedin-template').html());

	$.each(response.results.values, function(key, value) {

		// convert group feed to main feed
		if (value.creationTimestamp) {
			value.updateContent = {
				person: value.creator
			};
			value.status = value.summary.ParseURL();
			value.updateType = 'GroupPost';
			value.timestamp = value.creationTimestamp;
			value.isLiked = value.relationToViewer.isLiked;
			value.isCommentable = true;
			//value.updateComments = {values: value.comments.values};
			if (value.comments.values) {
				value.updateComments = {values: []};
				$.each(value.comments.values, function(i,e) {
					e.person = e.creator;
					e.comment = e.text;
					value.updateComments.values.push(e);
				});
			}
			value.numLikes = value.likes._total;
			delete(value.summary);
		}

		// store information about linkedin user
	//	elgg.deck_river.storeEntity(value.updateContent.person, 'linkedin');

		// format date and add friendly_time
		value.timestamp = value.timestamp/1000
		value.posted = new Date(value.timestamp);
		value.friendly_time = elgg.friendly_time(value.timestamp);

		value['type'+value.updateType] = true; // used for mustache

		if (value.updateContent.person && value.updateContent.person.currentStatus) value.updateContent.person.currentStatus.ParseURL();

		if (value.typeCONN) {
			value.summary = elgg.echo('deck_river:linkedin:CONN');
			var conn = value.updateContent.person.connections.values[0];
			value.typeCONN = {
				img: conn.pictureUrl,
				id: conn.id,
				name: conn.firstName + ' ' + conn.lastName,
				headline: conn.headline
			}
		} else if (value.typeSTAT) {
			value.status = value.updateContent.person.currentStatus.ParseURL();
		} else if (value.typeSHAR) {
			if(value.updateContent.person.currentShare){
				value.metadatas = value.updateContent.person.currentShare.content;
				if (value.updateContent.person.currentShare.comment) value.status = value.updateContent.person.currentShare.comment.ParseURL();
			}
		}

		output += linkedinRiverTemplate(value);
	});

	return $(output);
};


/**
 * show linkedin user popup
 * @param  {[string]} user screen_name
 */
elgg.deck_river.linkedinUserPopup = function(user, column) {
	elgg.deck_river.createPopup('user-info-popup', elgg.echo('deck_river:user-info-header', [user]));

	var body = $('#user-info-popup > .elgg-body'),
		userInfo = elgg.deck_river.findUser(user, 'linkedin'),
		templateRender = function(response) {
			body.html(Mustache.render($('#linkedin-user-profile-template').html(), response));
		};

	if (elgg.isUndefined(userInfo)) {
		elgg.action('deck_river/network/action', {
			dataType: 'json',
			data: {
				column_guid: column.closest('.column-river').attr('id'),
				params: 'people/id='+user+':(id,first-name,last-name,headline,location:(name),num-connections,num-connections-capped,num-recommenders,group-memberships,summary,picture-url,industry,distance,public-profile-url)',
				method: 'get'
			},
			success: function(response) {
				elgg.deck_river.storeEntity(response.output, 'linkedin');
				templateRender(response.output);
			},
			error: function() {
				body.html(elgg.echo('deck_river:ajax:erreur'));
			}
		});
	} else {
		templateRender(userInfo);
	}
};


// Linkedin user info popup
$('.linkedin-user-info-popup').on('click', function() {
	console.log($(this).attr('title'));
	elgg.deck_river.linkedinUserPopup($(this).attr('title'), $(this));
});


// Linkedin comment post
$('.linkedin-comment-form a').on('click', function() {
	console.log($(this));
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$column_guid = $cl.attr('id'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('id');

	elgg.action('deck_river/network/action', {
		data: {
			id: id,
			column_guid: $column_guid,
			method: 'post',
			params: 'people/~/network/updates/key='+id+'/update-comments',
			comment: $this.prev('textarea')
		},
		success: function(json) {
			elgg.system_message(elgg.echo('linkedin:updates:commented'));
		}
	});
});


// Linkedin like post
$('.elgg-menu-item-linkedin-like a').on('click', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$column_guid = $cl.attr('id'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('id');

	elgg.action('deck_river/network/action', {
		data: {
			id: id,
			column_guid:$column_guid,
			method: 'put',
			params: 'people/~/network/updates/key='+id+'/is-liked'
		},
		success: function(json) {
			$eli.find('.elgg-icon-star-sub').addClass('favorited');
			elgg.system_message(elgg.echo('linkedin:updates:favorited'));
		}
	});
});
