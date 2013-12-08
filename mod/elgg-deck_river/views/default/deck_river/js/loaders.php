/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river loaders js
 *
 */

// Nots : Be carefull ! Tweet IDs are too long for a call with .data('id') ! We need use .attr('data-id')

var //complete : FbRiverFields = "action_links,actor_id,app_data,app_id,attachment,attribution,claim_count,comment_info,created_time,description,description_tags,expiration_timestamp,feed_targeting,filter_key,impressions,is_exportable,is_hidden,is_published,like_info,message,message_tags,parent_post_id,permalink,place,post_id,privacy,description,promotion_status,scheduled_publish_time,share_count,share_info,source_id,subscribed,tagged_ids,target_id,targeting,timeline_visibility,type,updated_time,via_id,viewer_id,with_location,with_tags,xid",
	FbRiverFields = "actor_id,attachment,comments,comment_info,description,description_tags,likes,like_info,message,message_tags,parent_post_id,post_id,share_count,share_info,source_id,type,updated_time,via_id",
	// All fields from Facebook : "about_me,activities,affiliations,age_range,allowed_restrictions,birthday,birthday_date,books,can_message,can_post,contact_email,currency,current_address,current_location,devices,education,email,email_hashes,first_name,friend_count,friend_request_count,has_timeline,hometown_location,inspirational_people,install_type,interests,is_app_user,is_blocked,is_verified,languages,last_name,likes_count,locale,meeting_for,meeting_sex,middle_name,movies,music,mutual_friend_count,name,name_format,notes_count,online_presence,pic,pic_big,pic_big_with_logo,pic_cover,pic_small,pic_small_with_logo,pic_square,pic_square_with_logo,pic_with_logo,political,profile_blurb,profile_update_time,profile_url,proxied_email,quotes,relationship_status,religion,search_tokens,security_settings,sex,significant_other_id,sort_first_name,sort_last_name,sports,status,subscriber_count,third_party_id,timezone,tv,uid,username,verified,video_upload_limits,viewer_can_send_gift,wall_count,website,work",
	FbUserFields = "about_me,activities,affiliations,books,first_name,friend_count,inspirational_people,interests,languages,last_name,likes_count,locale,movies,music,mutual_friend_count,notes_count,online_presence,profile_update_time,profile_url,sex,status,subscriber_count,tv,uid,username,wall_count,website",
	FbPageFields = "";



/**
 * Load a column
 *
 * Makes Ajax call to persist column and inserts the column html
 *
 * @param {TheColumn} the column
 * @param {columnSettings} settings for load
 * @return void
 */
elgg.deck_river.LoadRiver = function(TheColumn, columnSettings) {
	var TheColumnHeader = TheColumn.addClass('loadingRefresh').find('.column-header'),
		TheColumnRiver = TheColumn.find('.elgg-river'),
		loadMoreItem = $('<li>', {'class': 'moreItem'}).append($('<li>', {'class': 'response-loader hidden'}), elgg.echo('deck_river:more'));

	if (columnSettings.direct) { // this is a direct link. Feed is loaded by user's browser.
		$.ajax({
			url: columnSettings.direct,
			dataType: 'jsonP',
			success: function(response) {
				TheColumnRiver.html(elgg.deck_river.displayRiver(response, columnSettings.network)).scrollTo(0);
				if (elgg.isUndefined(response.refresh_url)) {
					TheColumnHeader.data('refresh_url', TheColumnHeader.data('direct'));
				} else {
					TheColumnHeader.data('refresh_url', response.refresh_url);
				}
				if (!elgg.isUndefined(response.next_page)) {
					TheColumnRiver.append(loadMoreItem);
					TheColumnHeader.data('next_page', response.next_page);
				}
				TheColumn.removeClass('loadingRefresh');
			},
			error: function(xmlhttp, status, error) {
				elgg.register_error(elgg.echo('deck_river:twitter:access:error', [status, error]));
				TheColumn.removeClass('loadingRefresh');
			}
		});
	} else if (columnSettings.network == 'facebook') {
		var loadColumn = function() {
				if (columnSettings.type == 'stream') {
					elgg.deck_river.FBfql(columnSettings.token, {
						select: FbRiverFields,
						from: columnSettings.type,
						where: columnSettings.query,
						limit: 30
					}, function(response) {
						if (response && !response.error) {
							var rep = {};
							rep.TheColumn = TheColumn.removeClass('loadingRefresh');
							rep.results = ['1'];
							rep.data = response;
							rep.columnSettings = columnSettings;
							if (elgg.trigger_hook('deck-river', 'load:column:'+rep.column_type, rep, true)) {
								TheColumnRiver.html(elgg.deck_river.displayRiver(rep, columnSettings.network));
								TheColumnRiver.append(loadMoreItem).scrollTo(0);
								if (rep.paging) TheColumnHeader.data('next_page', rep.paging.next).data('refresh_url', rep.paging.previous);
							}
						} else { // @todo Make error more comprehensible
							TheColumnRiver.html('error');
						}
					});
				} else {
					FB.api(columnSettings.query, 'get', {
						access_token: columnSettings.token,
						fields: columnSettings.fields ? columnSettings.fields : FBdefaultFields,//(typeof(columnSettings) != 'undefined') ? columnSettings.fields : FBdefaultFields,
						limit: 30
					}, function(response) {
						if (response) {
							response.TheColumn = TheColumn.removeClass('loadingRefresh');
							response.results = ['1'];
							response.columnSettings = columnSettings;
							if (elgg.trigger_hook('deck-river', 'load:column:'+response.column_type, response, true)) {
								TheColumnRiver.html(elgg.deck_river.displayRiver(response, columnSettings.network));
								TheColumnRiver.append(loadMoreItem).scrollTo(0);
								if (response.paging) TheColumnHeader.data('next_page', response.paging.next).data('refresh_url', response.paging.previous);
							}
						} else { // @todo Make error more comprehensible
							TheColumnRiver.html('error');
						}
					});
				}
			};

		if (!FBloaded) {
			FBstackCallback.push(loadColumn);
		} else {
			loadColumn();
		}
	} else {
		var river_type = TheColumnHeader.data('river_type') || 'column_river';
		elgg.post('ajax/view/deck_river/ajax_json/' + river_type, {
			dataType: 'json',
			data: columnSettings,
			success: function(response) {
				if (response) {
					response.TheColumn = TheColumn.removeClass('loadingRefresh');
					if (elgg.trigger_hook('deck-river', 'load:column:'+response.column_type, response, true)) {
						if (response.column_message) elgg.deck_river.column_message(response.column_message, TheColumnHeader);
						if (response.column_error) elgg.deck_river.column_error(response.column_error, TheColumnHeader);
						TheColumnRiver.html(elgg.deck_river.displayRiver(response, columnSettings.network));
						if (!elgg.isString(response.results)) TheColumnRiver.append(loadMoreItem);
						TheColumnRiver.scrollTo(0);
					}
				} else { // @todo Make error more comprehensible
					TheColumnRiver.html('error');
				}
			}
		});
	}
};



/**
 * Refresh a column
 *
 * Makes Ajax call to persist column and inserts items at the beginig column html
 *
 * @param {TheColumn} the column
 * @param {columnSettings} the settings for this column
 * @return void
 */
elgg.deck_river.RefreshColumn = function(TheColumn, columnSettings) {
	var TheColumnHeader = TheColumn.addClass('loadingRefresh').find('.column-header'),
		TheColumnRiver = TheColumn.find('.elgg-river'),
		displayItems = function(response) {
			TheColumnHeader.find('.count').addClass('hidden').text('');
			TheColumn.removeClass('loadingRefresh').find('.elgg-list-item').removeClass('newRiverItem');
			response.TheColumn = TheColumn;
			if (elgg.trigger_hook('deck-river', 'refresh:column:'+response.column_type, response, true)) {
				var responseHTML = elgg.deck_river.displayRiver(response, columnSettings.network);

				if (!elgg.isUndefined(responseHTML)) {
					TheColumn.find('.elgg-river > table').remove();
					responseHTML.filter('.elgg-list-item').addClass('newRiverItem');
					if ($.browser.webkit) { // Need it because there is a bug with highlight in chrome. Need to be checked for next version of jqueryui
						TheColumn.find('.elgg-river').prepend(responseHTML).find('.newRiverItem');
					} else {
						TheColumn.find('.elgg-river').prepend(responseHTML).find('.newRiverItem').effect("highlight", 2000);
					}
					if (response.column_message) elgg.deck_river.column_message(response.column_message, TheColumnHeader);
					if (response.column_error) elgg.deck_river.column_error(response.column_error, TheColumnHeader);
					elgg.deck_river.displayCount(response, TheColumn);
				}
			}
		}

	if (columnSettings.direct) { // this is a direct link. Feed is loaded by user's browser.
		var url = elgg.parse_url(columnSettings.direct),
			refreshURL = columnSettings.refresh_url;
		$.ajax({
			url: refreshURL ? url.scheme+'://'+url.host+url.path + refreshURL : columnSettings.direct,
			dataType: 'jsonP',
			success: function(response) {
				displayItems(response);
				TheColumnHeader.data('refresh_url', response.refresh_url);
			}
		});
	} else if (columnSettings.network == 'facebook') {
		if (columnSettings.type == 'stream') {
			elgg.deck_river.FBfql(columnSettings.token, {
				select: FbRiverFields,
				from: 'stream',
				where: "filter_key='others' AND created_time>"+TheColumn.find('.elgg-list-item').first().attr('data-timeid'),
				limit: 30
			}, function(response) {
				if (response) {
					response.TheColumn = TheColumn;
					response.results = ['1'];
					response.data = response;
					response.columnSettings = columnSettings;
					displayItems(response);
					if (response.paging) TheColumnHeader.data('refresh_url', response.paging.previous);
				} else { // @todo Make error more comprehensible
					TheColumnRiver.html('error');
				}
			});
		} else {
			FB.api(columnSettings.query, 'get', {
				access_token: columnSettings.token,
				fields: (typeof(columnSettings) != 'undefined') ? columnSettings.fields : FBdefaultFields,
				__previous: 1,
				since: TheColumnHeader.data('refresh_url').match(/.*since=(\d*)/)[1],
				limit: 30
			}, function(response) {
				if (response) {
					response.TheColumn = TheColumn;
					response.results = ['1'];
					response.columnSettings = columnSettings;
					displayItems(response);
					if (response.paging) TheColumnHeader.data('refresh_url', response.paging.previous);
				} else { // @todo Make error more comprehensible
					TheColumnRiver.html('error');
				}
			});
		}
	} else {
		var river_type = TheColumnHeader.data('river_type') || 'column_river';
		elgg.post('ajax/view/deck_river/ajax_json/' + river_type, {
			dataType: 'json',
			data: {
				tab: columnSettings.tab,
				column: columnSettings.column,
				time_method: 'lower',
				time_posted: TheColumn.find('.elgg-list-item').first().attr('data-timeid') || 0
			},
			success: function(response) {displayItems(response)}
		});
	}
};



/**
 * Load more item in a column
 *
 * Makes Ajax call to persist column and inserts items at the end of the column html
 *
 * @param {TheColumn} the column
 * @return void
 */
elgg.deck_river.LoadMore = function(TheColumn, columnSettings) {
	var TheColumnHeader = TheColumn.addClass('loadingMore').find('.column-header'),
		LastItem = TheColumn.find('.elgg-list-item').removeClass('newRiverItem').last(),
		displayItems = function(response) {
			var TheColumnRiver = TheColumn.removeClass('loadingMore').find('.elgg-river'),
				responseHTML = elgg.deck_river.displayRiver(response, columnSettings.network);

			TheColumnHeader.find('.count').addClass('hidden');
			if ($.browser.webkit) { // Need it because there is a bug with highlight in chrome. Need to be checked for next version of jqueryui
				TheColumnRiver.append(responseHTML)
					.find('.moreItem').appendTo(TheColumnRiver);
			} else {
				TheColumnRiver.append(responseHTML.effect("highlight", 2000))
					.find('.moreItem').appendTo(TheColumnRiver);
			}
		};

	if (columnSettings.direct) { // this is a direct link. Feed is loaded by user's browser.
		var url = elgg.parse_url(columnSettings.direct);
		$.ajax({
			url: url.scheme+'://'+url.host+url.path + columnSettings.next_page,
			dataType: 'jsonP',
			success: function(response) {
				if (elgg.isUndefined(response.next_page)) response.next_page = TheColumnHeader.data('next_page').match('^.*=')[0] + response[response.length-1].id_str.addToLargeInt(-1);
				TheColumnHeader.data('next_page', response.next_page);
				displayItems(response);
			},
			error: function() {
				TheColumn.removeClass('loadingMore');
			}
		});
	} else if (columnSettings.network == 'facebook') {
		if (columnSettings.type == 'stream') {
			elgg.deck_river.FBfql(columnSettings.token, {
				select: FbRiverFields,
				from: 'stream',
				where: "filter_key='others' AND created_time<"+LastItem.data('timeid'),
				limit: 30
			}, function(response) {
				if (response) {
					response.TheColumn = TheColumn;
					response.results = ['1'];
					response.data = response;
					response.columnSettings = columnSettings;
					displayItems(response);
					TheColumnHeader.data('next_page', response.paging.next);
				} else { // @todo Make error more comprehensible
					TheColumnRiver.html('error');
				}
			});
		} else {
			FB.api(columnSettings.query, 'get', {
				access_token: columnSettings.token,
				fields: (typeof(columnSettings) != 'undefined') ? columnSettings.fields : FBdefaultFields,
				until: TheColumnHeader.data('next_page').match(/.*until=(\d*)/)[1],
				limit: 30
			}, function(response) {
				if (response) {
					response.TheColumn = TheColumn;
					response.results = ['1'];
					response.columnSettings = columnSettings;
					displayItems(response);
					TheColumnHeader.data('next_page', response.paging.next);
				} else { // @todo Make error more comprehensible
					TheColumnRiver.html('error');
				}
			});
		}
	} else {
		var river_type = TheColumnHeader.data('river_type') || 'column_river';
		elgg.post('ajax/view/deck_river/ajax_json/' + river_type, {
			dataType: 'json',
			data: {
				tab: columnSettings.tab,
				column: columnSettings.column,
				time_method: 'upper',
				time_posted: LastItem.data('timeid'),
				entity: columnSettings.entity || null,
				params: columnSettings.params || null,
				types_filter: columnSettings.types_filter,
				subtypes_filter: columnSettings.subtypes_filter
			},
			success: function(response) {displayItems(response)},
			error: function() {
				TheColumn.removeClass('loadingMore');
			}
		});
	}
};



/**
 * Load a discussion
 *
 * Makes Ajax call to load discussion if doesn't exist and inserts items after the river item
 *
 * @param {athread} the wire thread
 * @return void
 */
elgg.deck_river.LoadDiscussion = function(athread) {
	var athreadResponses = athread.parent('.elgg-river-responses'),
		TheColumn = athread.closest('.column-river'),
		network = athread.data('network');

	// if already exist, skip
	if (athreadResponses.find('div.thread').length) return;

	athreadResponses.find('.response-loader').removeClass('hidden');

	elgg.post('ajax/view/deck_river/ajax_json/load_discussion', {
		dataType: "json",
		data: {
			discussion: athread.attr('data-thread'),
			network: network
		},
		success: function(response) {
			var riverID = athread.closest('.elgg-list-item').attr('class').match(/\d+/)[0],
				itemsRiver = $('.item-' + network + '-' + riverID),
				newItems = elgg.deck_river.displayRiver(response, network, true);

			if (response.results) {
				$.each(itemsRiver, function() {
					var idToggle = $(this).find('.response-loader').addClass('hidden')
						.closest('.column-river').attr('id') + '-' + riverID;

					$(this).find('.elgg-river-responses')
						.append($('<div>', {id: idToggle, 'class': 'thread mts float hidden'}).html(newItems.clone()))
					.find('a.thread').attr({
						rel: 'toggle',
						href: '#' + idToggle
					});
				});
				athread.click(); // toggle after append
			}
		},
		error: function(response) {
			elgg.register_error(response.responseText);
			athread.parent('.elgg-river-responses').find('.response-loader').addClass('hidden');
		}
	});
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




/*
 * Display number of new items
 */
elgg.deck_river.displayCount = function(response, TheColumn) {
	var TheColumnHeader = TheColumn.find('.column-header'),
		TheColumnRiver = TheColumn.find('.elgg-river'),
		responseLength = elgg.isUndefined(response['data']) ? response['results'].length : response['data'].length,
		countSpan = TheColumnHeader.find('.count').addClass('hidden');

	if (responseLength > 0) {
		countSpan.removeClass('hidden').text(responseLength);
		if (TheColumnRiver.scrollTop() > 50) {
			if (TheColumn.find('.top-message').length) {
				TheColumn.find('.top-message').html(elgg.echo('deck_river:column:gotop', [responseLength]));
			} else {
				$('<li>', {'class': 'top-message', text: elgg.echo('deck_river:column:gotop', [responseLength])}).click(function() {
					TheColumnRiver.scrollTo(0, 500, {easing:'easeOutQuart'});
				}).appendTo(TheColumn.find('.message-box')).effect('slide',{direction: 'up'}, 300);
				TheColumnRiver.unbind('scroll.topMessage').bind('scroll.topMessage', function() {
					if($(this).scrollTop() == 0) {
						TheColumn.find('.top-message').toggle('slide', {direction: 'up'}, 300, function() {$(this).remove()});
					}
				});
			}
		}
		// column is hidden by #deck-river-lists scroll ?
		if (TheColumn.position().left < -TheColumn.width()+80) { // hidden at left
			var c = $('.elgg-page-body .deck-river-scroll-arrow.left div');
			(c.html() == '') ? c.html(responseLength) : c.html(parseInt(c.html()) + responseLength);
		} else if (TheColumn.position().left + TheColumn.width()-15 > TheColumn.closest('#deck-river-lists').width()) { //hidden at right
			var c = $('.elgg-page-body .deck-river-scroll-arrow.right div');
			(c.html() == '') ? c.html(responseLength) : c.html(parseInt(c.html()) + responseLength);
		}
	}
};



/**
 * Displays messages in column
 *
 * @param {String} msgs The message we want to display
 * @param {dom} TheColumn The column we want to display
 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to 6 seconds.
 * @param {String} type The type of message (typically 'error' or 'message')
 * @private
 */
elgg.deck_river.column_messages = function(msgs, TheColumnHeader, delay, type) {
	if (elgg.isUndefined(msgs)) return;

	var classes = ['column-message'],
		messages_html = [],
		appendMessage = function(msg) {
			messages_html.push('<li class="' + classes.join(' ') + '"><p>' + msg + '</p></li>');
		};

	//validate delay.  Must be a positive integer.
	delay = parseInt(delay || 6000, 10);
	if (isNaN(delay) || delay <= 0) {
		delay = 6000;
	}

	//Handle non-arrays
	if (!elgg.isArray(msgs)) msgs = [msgs];

	if (type === 'error') {
		classes.push('elgg-state-error');
	} else {
		classes.push('elgg-state-success');
	}

	msgs.forEach(appendMessage);
	TheColumnHeader.parent().find('.column-messages').append($(messages_html.join('')))
		.effect('slide',{direction: 'up'}, 300).delay(delay).toggle('slide',{direction: 'up'}, 300, function() {$(this).html('')});
};

/**
 * Wrapper function for column_messages. Specifies "messages" as the type of message
 * @param {String} msgs  The message to display
 * @param {dom} TheColumn The column we want to display
 * @param {Number} delay How long to display the message (milliseconds)
 */
elgg.deck_river.column_message = function(msgs, TheColumn, delay) {
	elgg.deck_river.column_messages(msgs, TheColumn, delay, "message");
};

/**
 * Wrapper function for column_messages.  Specifies "errors" as the type of message
 * @param {String} errors The error message to display
 * @param {dom} TheColumn The column we want to display
 * @param {Number} delay  How long to dispaly the error message (milliseconds)
 */
elgg.deck_river.column_error = function(errors, TheColumn, delay) {
	elgg.deck_river.column_messages(errors, TheColumn, delay, "error");
};



