<?php if (0): ?><script><?php endif; ?>

// Get url of networks to authorize user
$(document).on('click', '#authorize-tumblr', function(){
	var network = $(this).attr('id').replace('authorize-', '');

	window.open(elgg.get_site_url() + 'authorize/tumblr', 'ConnectWithOAuth', 'location=0,status=0,width=800,height=400');

	return false;
});


/**
 * Tumblr Display *
 * @param {array}	json response
 */
elgg.deck_river.tumblrDisplayItems = function(response) {
	var output = '',
		imgs = [],
		tumblrRiverTemplate = Mustache.compile($('#elgg-river-tumblr-template').html());

	$.each(response.results.posts, function(key, value) {

		// store information about tumblr user
		elgg.deck_river.storeEntity(value.user, 'tumblr');

		// format date and add friendly_time
		value.posted = new Date(value.date);
		value.friendly_time = elgg.friendly_time(value.timestamp);

		value['type'+value.type] = true; // used for mustache

		// store picture
		if (value.photos) {
			$.each(value.photos, function(i, e) {
				imgs.push({src: e.original_size.url, id: value.id+'-'+i});
				value.photos[i].img_id = value.id+'-'+i;
			});
		}

		output += tumblrRiverTemplate(value);
	});

	// resize images
	$.each(imgs, function(i, e) {
		var img = new Image();

		img.src = e.src;
		img.onload = function() {
			var tw = this.width, th = this.height,
				$i = $('#img'+e.id),
				$eri = $i.data('img', [tw, th]).parent();

			if (tw >= $eri.width() || tw >= 600) {
				$i.height(Math.min($eri.addClass('big').width(), '600', tw)/tw*th);
			}
			if (tw <= 1) $i.remove(); // Don' know why, but sometimes facebook return a "safe_image" with 1x1 pixels
		};
		img.onerror = function() {
			$('#img'+e.id).remove();
		};

	});

	return $(output);
};


/**
 * show tumblr user popup
 * @param  {[string]} user screen_name
 */
elgg.deck_river.tumblrUserPopup = function(user, column) {
	elgg.deck_river.createPopup('user-info-popup', elgg.echo('deck_river:user-info-header', [user]));

	var body = $('#user-info-popup > .elgg-body'),
		userInfo = elgg.deck_river.findUser(user, 'tumblr'),
		templateRender = function(response) {
			body.html(Mustache.render($('#tumblr-user-profile-template').html(), response));
		};

	if (elgg.isUndefined(userInfo)) {
		elgg.post('ajax/view/deck_river/networks/tumblr/tumblr_OAuth', {
			dataType: 'json',
			data: {
				column_guid: $(this).closest('.column-river').attr('id'),
				params: {method: 'blog/'+user+'.tumblr.com/info'}
			},
			success: function(response) {
				elgg.deck_river.storeEntity(response, 'tumblr');
				templateRender(response);
			},
			error: function() {
				body.html(elgg.echo('deck_river:ajax:erreur'));
			}
		});
	} else {
		templateRender(userInfo);
	}
};


// Tumblr user info popup
$('.tumblr-user-info-popup').on('click', function() {
	elgg.deck_river.tumblrUserPopup($(this).attr('title'), $(this));
});

// Tumblr like post
$('.elgg-menu-item-tumblr-like a').on('click', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$column_guid = $cl.attr('id'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('id');

	elgg.action('deck_river/network/action', {
		data: {
			id: id,
			column_guid:$column_guid,
			method: 'user/like',
			params: {
				id: id,
				reblog_key: $eli.data('reblog_key')
			}
		},
		dataType: 'json',
		success: function(json) {
			var $count = $eli.find('.elgg-icon-star-sub').addClass('favorited').next();
			$count.html(parseInt($count.text())+1);
			elgg.system_message(elgg.echo());
		}
	});
});

<?php if (0): ?></script><?php endif; ?>

