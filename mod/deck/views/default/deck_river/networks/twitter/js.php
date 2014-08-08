<?php if (0): ?><script><?php endif; ?>
/**
 * Twitter Display *
 * @param {array}	json response
 */
elgg.deck_river.twitterDisplayItems = function(response, thread) {
	var output = '',
		elggRiverTemplate = Mustache.compile($('#elgg-river-twitter-template').html());

	$.each(response.results, function(key, value) {
		var retweet = false,
			reply = false;

		if (!response.column_type) { // direct link. json returned by Twitter is different between twitter search api and twitter main api
		//	value.user = {screen_name: value.from_user, profile_image_url_https: value.profile_image_url_https};
		} else if (response.column_type == 'get_direct_messages') { // json is different with direct_messages
			value.user = value.sender;
		} else if (response.column_type == 'get_direct_messagesSent') { // json is different with direct_messages
			value.user = value.recipient;
		}

		// store information about twitter user
		elgg.deck_river.storeEntity(value.user, 'twitter');

		// this is a reteweet
		if (value.retweeted_status) {
			var which = ' <span class="twitter-user-info-popup" title="' + value.user.screen_name + '">' + value.user.screen_name + '</span>';

			if (value.retweet_count === 1) {
				retweet = elgg.echo('retweeted_by', [which]);
			} else { // there is retweeted_satus so if is not 1 this is > 1
				retweet = elgg.echo('retweeted_which', [value.retweet_count, which]);
			}

			delete value.retweeted_status.created_at; // we remove this key to keep created_at and id_str of last retweet.
			delete value.retweeted_status.id_str;
			$.extend(value, value.retweeted_status); // retweet_status contain all information about origin tweet, so we swich it.

			elgg.deck_river.storeEntity(value.user, 'twitter'); // store original user
		} else if (value.retweet_count === 1) {
			retweet = elgg.echo('retweet:one');
		} else if (value.retweet_count > 1) { // there is retweeted_satus so if is not 1 this is > 1
			retweet = elgg.echo('retweet:twoandmore', [value.retweet_count]);
		}

		// format date and add friendly_time
		value.posted = value.created_at.FormatDate();
		value.friendly_time = elgg.friendly_time(value.posted);
		if (value.source) {
			value.source = value.source[0] == '&' ? $('<div>').html(value.source).text() : value.source ; // twitter search api retun encoded string, not main api
		}

		// make menus
		if (!thread) {
			var fav = value.favorited;
			value.submenu = [{
				name: fav ? 'star-empty' : 'star',
				content: elgg.echo('action:'+(fav ? 'unfav' : 'fav')+'orite'),
				method: fav ? 'post_favoritesDestroy' : 'post_favoritesCreate',
				options: JSON.stringify({id: value.id_str})
			}];
			// add replyall in submenu
			if (/@\w{1,}/g.test(value.text)) {
				value.submenu.push({
					name: 'response-all',
					content: elgg.echo('replyall')
				});
			}
		} else {
			value.thread = true;
		}

		// Fill responses (retweet and discussion link)
		if (value.favorited && !value.favorite_count) value.favorite_count = 1;
		value.responses = {
			retweet: retweet ? retweet : false,
			favorite: value.favorite_count ? elgg.echo('favori'+(value.favorite_count>1?'s':'')) : false,
			reply: value.in_reply_to_status_id != null && !thread // thread id is filled by id_str in mustache template. Only true/false is sending.
		};

		// parse tweet text
		value.message = value.text.ParseTwitterURL(value.entities).ParseUsername('twitter').ParseHashtag('twitter');

		output += elggRiverTemplate(value);

	});
	return $(output);
};
/*
 * Load Twitter timeline for an user
 */
elgg.deck_river.LoadTwitter_activity = function(twitterID, OutputElem) {
	var OutputElemHeader = OutputElem.find('.column-header'),
		url = elgg.parse_url(OutputElemHeader.data('direct'));
	$.ajax({
		url: url.scheme+'://'+url.host+url.path+'?count=50&include_rts=1&user_id='+twitterID,
		dataType: 'jsonP',
		success: function(response) {
			OutputElem.find('.elgg-river').html(elgg.deck_river.twitterDisplayItems(response))
				.append($('<li>', {'class': 'moreItem'}).append($('<li>', {'class': 'response-loader hidden'}), elgg.echo('deck_river:more')));
			OutputElemHeader.data('next_page',
				'?count=50&include_rts=1&user_id='+twitterID+'&max_id='+ response[response.length-1].id_str.addToLargeInt(-1));
		},
		error: function(xmlhttp, status, error) {
			//OutputElem.find('.elgg-river').html();
			elgg.register_error(elgg.echo('deck_river:twitter:access:error', [status, error]));
		}
	});
};

// Get url of networks to authorize user
$(document).on('click', '#authorize-twitter', function(){
	var network = $(this).attr('id').replace('authorize-', '');

	window.open(elgg.get_site_url() + 'authorize/twitter', 'ConnectWithOAuth', 'location=0,status=0,width=800,height=400');
	
	return false;
});

/**
 * Retweet used in Twitter feed or Facebook feed
 * @return {[type]} [description]
 */
$('.elgg-menu-item-retweet a').on('click', function() {
	var $eli = $(this).closest('.elgg-list-item'),
		columnSettings = elgg.deck_river.getColumnSettings($(this).closest('.column-river'));

	if (columnSettings.network == 'facebook' || $eli.find('.elgg-river-image').length) {
		var data = $eli.find('.elgg-river-image').data();

		data = $.extend({
			mainimage: '',
			url: false,
			description: '',
			tilte: false,
			text: false
		}, data);
		//data.editable = false;

		$('#linkbox').removeClass('hidden').html(Mustache.render($('#linkbox-template').html(), data));
		if (data.text) $('#thewire-textarea').val($('<div>').html(data.text).text());
		if (data.url) linkParsed = data.url.replace(/\/$/, '');
		elgg.thewire.resize();

		// Facebook API doesn't have a share method. I find somethings that provide a way to share post. But it seem it doesn't realy work...
		// Here the link that explain share method : http://stackoverflow.com/questions/13149854/facebook-api-share-a-post-already-posted-on-a-pages-wall#13250197
		// See actions/message/add too.
		// elgg.deck_river.responseToWire($eli, $eli.find('.elgg-river-message.main').data('message_original'));
	} else {
		$('#thewire-textarea').val('RT @'+$eli.data('username')+' '+$('<div>').html($eli.data('text')).text().replace(/^rt /i, '').replace(/\s+/g, ' ')).focus().keydown();
	}
});

/**
 * Twitter action : follow, unfollow, add to list...
 * @return {[type]} [description]
 */
$('a[twitter_action]').on('click', function(e) {
	var data = $(this).data(),
		accounts = $('#thewire-network .net-profile.twitter');

	if (accounts.length > 1 && elgg.isUndefined(data.twitter_account)) { // choose account
		var accountsString = '';

		elgg.deck_river.createPopup('choose-twitter-account-popup', elgg.echo('deck_river:twitter:choose_account'), function() {
			$('#choose-twitter-account-popup').find('.elgg-icon-push-pin').remove();
		});

		$.each(accounts, function(i, e) {
			accountsString += Mustache.render($('#choose-twitter-account-template').html(), {
				method: data.method,
				options: JSON.stringify(data.options),
				account: $(e).find('input').val(),
				name: $(e).find('.twitter-user-info-popup').attr('title')
			});
		});
		$('#choose-twitter-account-popup > .elgg-body').html('<ul>'+accountsString+'</ul>');
	} else if (!elgg.isUndefined(data.options.list_id) && data.options.list_id === "") { // choose list
		elgg.deck_river.createPopup('choose-twitter-list-popup', elgg.echo('deck_river:twitter:choose_list'), function() {
			$('#choose-twitter-list-popup').find('.elgg-icon-push-pin').remove();
			elgg.action('deck_river/twitter', {
				data: {
					twitter_account: data.twitter_account,
					method: 'get_listsList'
				},
				dataType: 'json',
				success: function(json) {
					var listsString = $('<ul>');

					$.each(json.output.result, function(i, e) {
						listsString.append($('<li>').append($('<a>', {
							href: '#',
							text: e.full_name,
							twitter_action: true,
							'data-options': JSON.stringify($.extend(data.options, {list_id: e.id}))
						}).data({
							method: data.method,
							twitter_account: data.twitter_account
						})));
					});
					if ($(listsString).html() == '') listsString = elgg.echo('deck_river:twitter:no_lists');
					$('#choose-twitter-list-popup > .elgg-body').html(listsString);
				},
				error: function() {
					return false;
				}
			});
		});
	} else {
		elgg.action('deck_river/twitter', {
			data: {
				twitter_account: data.twitter_account || accounts.find('input').val(),
				method: data.method,
				options: data.options
			},
			dataType: 'json',
			success: function(json) {
				if (!elgg.isUndefined(json.output.result) && json.status > -1) {
					var response = json.output.result,
						echoArray = [],
						method = data.method.replace(/\d/g, '');

					if (method == 'post_friendshipsCreate' || method == 'post_friendshipsDestroy') {
						// strange ? Twitter return count before following or unfollowing action ?? And following is not populate.
						if (method == 'post_friendshipsCreate') {
							//response.followers_count++;
							response.following = true;
						} else {
							//response.followers_count--;
							response.following = false;
						}
						elgg.deck_river.storeEntity(response, 'twitter');
						elgg.deck_river.twitterUserPopup(response.screen_name);
						echoArray = [response.screen_name];
					}
					if (method == 'post_favoritesCreate' || method == 'post_favoritesDestroy' || method == 'post_statusesRetweet') {
						$('.elgg-river > .item-twitter-'+data.options.id).replaceWith(elgg.deck_river.twitterDisplayItems({
							results: [response],
							column_type: true
						}));
						echoArray = [response.user.screen_name, response.full_name];
					}

					$('#choose-twitter-account-popup, #choose-twitter-list-popup').remove();
					elgg.system_message(elgg.echo('deck_river:twitter:post:'+method, echoArray));
				}
			},
			error: function() {
				elgg.register_error(elgg.echo('deck_river:twitter:error'));
			}
		});
	}

	return false;
});
String.prototype.ParseTwitterURL = function(entities) {
	var text = this,
		urls = [],
		replaceEntities = function(type) {
			$.each(entities[type], function(i, e) {
				var token = (Math.random()+'xxxxxxxxxxxxxxxx').replace('.', '').substr(0, e.indices[1]-e.indices[0]),
					url = '',
					iframeUrl = null;

				if (type == 'media') {
					url = '<a class="media-image-popup" href="'+e.media_url_https+'" onclick="javascript:void(0)" data-media="'+e.media_url_https+'" data-type="'+e.type+'" data-size_width="'+e.sizes.medium.w+'" data-size_height="'+e.sizes.medium.h+'">'+e.display_url+'</a>';
				} else if (iframeUrl = elgg.deck_river.setVideoURLToIframe(e.expanded_url)) {
					url = '<a class="media-video-popup" href="'+e.expanded_url+'" onclick="javascript:void(0)" data-source="'+iframeUrl+'">'+e.display_url+'</a>';
				} else {
					url = '<a target="_blank" rel="nofollow" href="'+e.expanded_url+'">'+e.display_url+'</a>';
				}

				urls.push({
					token: token,
					url: url
				});
				text = text.substr(0, e.indices[0]) + token + text.substr(e.indices[1], text.length);
			});
		};

	if (entities.urls) replaceEntities('urls');
	if (entities.media) replaceEntities('media');
	$.each(urls, function(i, e) {
		text = text.replace(e.token, e.url);
	});
	return text.ParseURL(); // some link are not in entities
};
/**
 * Returns the length of Tweet text with consideration to t.co URL replacement
 * and chars outside the basic multilingual plane that use 2 UTF16 code points
 * These come from https://api.twitter.com/1/help/configuration.json
 * described by https://dev.twitter.com/docs/api/1/get/help/configuration
 *
 * @param  {array}  urls    an array of urls in the text
 * @return {integer}        length of the text
 */
String.prototype.getTweetLength = function(urls) {
	var urls_length = 0,
		tco_urls_length = 0;

	if (urls) {
		$.each(urls, function(i, e) {
			urls_length += e.length;
			/^https/.test(e) ? tco_urls_length += 23 : tco_urls_length += 22;
		});
		return this.length - urls_length + tco_urls_length;
	} else {
		return this.length;
	}
};


<?php if (0): ?></script><?php endif; ?>

