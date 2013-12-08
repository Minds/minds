/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river river templates js
 *
 */



/**
 * Return html river
 */
elgg.deck_river.displayRiver = function(response, network, thread) {
	var network = network || 'elgg',
		thread = thread || false;

	if (elgg.isString(response.results)) {
		return $(response.results);
	} else if (response.results && response.results.length != 0) {
		return elgg.deck_river[network + 'DisplayItems'](response, thread);
	}
};



/**
 * Javascript template for river element @todo waiting for Elgg core developers to see wich library they will use (ember.js, ...) in elgg 1.9 or 2 and replace it with a js MVC system.
 *
 * @param {array}	json response
 */
elgg.deck_river.elggDisplayItems = function(response, thread) {
	var output = '',
		elggRiverTemplate = Mustache.compile($('#elgg-river-template').html());

	// Put users and groups in global var DataEntities
	$.each(response.users, function(i, entity) {
		elgg.deck_river.storeEntity(entity);
	});

	$.each(response.results, function(key, value) {

		// add user object
		value.user = $.grep(response.users, function(e){ return e.guid == value.subject_guid; })[0];
		// add friendly_time
		value.friendly_time = elgg.friendly_time(value.posted);

		value.text = elgg.isArray(value.message) ? null : value.message;
		if (value.type == 'object' && value.text) {
			value.message = value.text.ParseGroup().ParseURL(true, true).ParseUsername('elgg').ParseHashtag('elgg');
			value.text = $('<div>').html(value.text).text();
		}

		if (value.method == 'site') delete value.method;

		// Remove responses if in thread
		if (thread && !elgg.isNull(value.responses)) delete value.responses;
		if (thread) delete value.menu;

		output += elggRiverTemplate(value);

	});
	return $(output);
};



/**
 * Javascript template for river element @todo waiting for Elgg core developers to see wich library they will use (ember.js, ...) in elgg 1.9 or 2 and replace it with a js MVC system.
 *
 * @param {array}	json response
 */
elgg.deck_river.twitterDisplayItems = function(response, thread) {
	var output = '',
		elggRiverTemplate = Mustache.compile($('#elgg-river-twitter-template').html());

	$.each(response.results, function(key, value) {
		var retweet = false,
			reply = false;

		if (!response.column_type) { // direct link. json returned by Twitter is different between twitter search api and twitter main api
			value.user = {screen_name: value.from_user, profile_image_url_https: value.profile_image_url_https};
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



/**
 * Javascript template for river element @todo waiting for Elgg core developers to see wich library they will use (ember.js, ...) in elgg 1.9 or 2 and replace it with a js MVC system.
 *
 * @param {array}	json response
 */
elgg.deck_river.facebookDisplayItems = function(response, thread) {
	var output = '',
		imgs = [],
		doublePass = [],
		elggRiverTemplate = Mustache.compile($('#elgg-river-facebook-template').html());
		Mustache.compilePartial('erFBt-comment', $('#erFBt-comment').html()),
		checkAction = function(actions, action) {
			var ret = false;

			if (elgg.isUndefined(actions)) return false;
			$.each(actions, function(i,e) {
				if (e.name == action) ret = true;
			});
			return ret;
		};

	if (response.columnSettings.type == 'stream') {
		var FBusers = [],
			FBusers_temp = [],
			ObjTemp = {};

		// get all users/pages from response
		$.each(response.data, function(i, e) {
			FBusers_temp.push(e.source_id);
			if (e.via_id) FBusers_temp.push(e.via_id);
			if (e.comments.comment_list.length) {
				$.each(e.comments.comment_list, function(i, e) {
					FBusers_temp.push(e.fromid);
				});
			}
		});
		// remove duplicates and check if we already got it
		$.each(FBusers_temp, function(i, e) {
			ObjTemp[FBusers_temp[i]] = true;
		});
		for (var k in ObjTemp) FBusers.push(k);

		// get users from Facebook
		// !! this is an ajax function, it will be executed on response ! So, we fill column (see code behind) and we complete items after this response.
		elgg.deck_river.FBfql(response.columnSettings.token, {
			select: 'can_post,id,name,pic,pic_big,pic_crop,pic_small,pic_square,type,url,username',
			from: 'profile',
			where: 'id IN ('+ FBusers.join(',') +')'
		}, function(users) {
			if (users && !users.error_code) {

				$.each(response.data, function(i, e) {
					var rd = response.data[i],
						user = $.grep(users, function(e) {return e.id == rd.actor_id})[0];

					if (user) {
						rd.from = {
							id: user.id,
							name: user.name,
							category: (user.type == 'user') ? false : true
						};
					}
					if (rd.comments.data.length) {
						$.each(rd.comments.data, function(j, f) {
							user = $.grep(users, function(e) {return e.id == f.from.id})[0];

							if (user) {
								rd.comments.data[j].from = {
									id: user.id,
									name: user.name,
									category: (user.type == 'user') ? false : true
								};
							}
						});
					}

					response.TheColumn.find('.item-facebook-'+rd.id).replaceWith(elggRiverTemplate(rd));
					elgg.deck_river.resizeRiverImages();
				});


			}
		});

		// change and add some datas
		$.each(response.data, function(i, e) {
			var rd = response.data[i],
				post_id = rd.post_id.split('_');

			if (rd.attachment.media && rd.attachment.media.length) {
				$.extend(response.data[i], {
					full_picture: rd.attachment.media[0].src,
					type: rd.attachment.media[0].type
				});
				rd.attachment.href = rd.attachment.media[0].href;
			}
			$.extend(response.data[i], {
				id: post_id[1],
				from: {id: post_id[0], name: ''},
				icon: rd.attachment.icon,
				name: rd.attachment.name,
				link: (/https?:\/\/fbexternal/.test(rd.attachment.href)) ? decodeURIComponent(rd.attachment.href.match(/url=(.*(?:\&|$))/)[1]) : rd.attachment.href,
				story: rd.description,
				story_tags: rd.description_tags,
				description: rd.attachment.description,
				caption: rd.attachment.caption,
				can_like: rd.like_info.can_like,
				can_comment: rd.comment_info.can_comment,
				shares: {count: parseInt(rd.share_count)}
			});
			if (!rd.shares.count) delete rd.shares;
			if (rd.comments) {
				var com_list = [];
				$.each(rd.comments.comment_list, function(i, e) {
					var com = rd.comments.comment_list[i];
					com_list.push({
						can_comment: rd.comments.can_post,
						can_remove: rd.comments.can_remove,
						from: {id: com.fromid, name: null},
						created_time: com.time,
						like_count: parseInt(com.likes),
						message: com.text
					});
				});
				$.extend(rd.comments, {
					data: com_list
				});
			}
			if (rd.likes.count != "0") {
				rd.likes.data = [];
				$.each($.unique(rd.likes.friends.concat(rd.likes.sample)), function(i,e) {
					rd.likes.data.push({id: e, username: null});
				});
			} else {
				delete rd.likes;
			}
		});

	}

	$.each(response.data, function(key, value) {

		// format date and add friendly_time
		if (!value.updated_time) value.updated_time = value.created_time;
		value.posted = /^\d+$/.test(value.updated_time) ? value.updated_time : value.updated_time.FormatDate();
		value.friendly_time = elgg.friendly_time(value.posted);

		// Add some info in message from story
		if (!value.message) {
			if (/was tagged/.test(value.story) || /commented on/.test(value.story) || /likes a/.test(value.story)) {
				doublePass.push(value.id);
			} else if (/shared a link/.test(value.story)) {
				value.summary = elgg.echo('deck_river:facebook:summary:shared_link', [value.link]);
			} else if (/shared an event/.test(value.story) || / event\./.test(value.story)) {
				value.summary = elgg.echo('deck_river:facebook:summary:shared_event', [value.link]);
				value.name = value.link;
			} else if (/'s status update/.test(value.story)) {
				value.summary = elgg.echo('deck_river:facebook:summary:shared_status', [value.link]);
				var st = value.story_tags[Object.keys(value.story_tags)[Object.keys(value.story_tags).length - 1]][0];
				value.via = {id: st.id, name: st.name};
				if (st.type == 'page') value.via.category = 1;
			} else if (/updated his cover photo/.test(value.story)) {
				value.summary = elgg.echo('deck_river:facebook:summary:updated_cover_photo');
			} else if (/'s photo/.test(value.story)) {
				if (/Timeline Photos?/.test(value.name)) {
					delete value.name;
					if (/https?:\/\/fbcdn/.test(value.full_picture)) value.full_picture = value.full_picture.replace(/_s\./, '_n.');
					value.message = value.caption;
					delete value.caption;
				}
			} else {
				value.message = value.story;
			}
		}
		// parse message
		if (value.message) {
			value.message_original = value.message;
			value.message = value.message.TruncateString().ParseURL().ParseUsername('twitter').ParseHashtag('facebook');
		}
		// add show on facebook for status
		if (value.type == 'status' && value.status_type != 'mobile_status_update') value.showOnFacebook = elgg.echo('river:facebook:show:status');

		if (value.properties && value.properties[0].name == 'Length') delete value.properties; // ugly code returned by Facebook

		if (value.likes) {
			var vld = value.likes.data,
				count = parseInt(value.likes.count) || vld.length,
				u = '';

			value.likes.string = elgg.echo('deck_river:facebook:like'+(count == 1 ? '':'s'), [count]);
			$.each(vld, function(i, e) {
				u += ','+e.id;
				if (response.columnSettings && e.id == response.columnSettings.user_id) value.liked = true;
			});
			value.likes.users = u.substr(1);
		}
		if (value.shares) {
			var vsc = value.shares.count;
			value.shares.string = elgg.echo('deck_river:facebook:share'+(vsc == 1 ? '':'s'), [vsc]);
		}

		if (value.comments) {
			var vcd = value.comments.data;
			$.each(vcd, function(i,e) {
				var ef = value.comments.data[i].posted = /^\d+$/.test(e.created_time) ? e.created_time : e.created_time.FormatDate();
				value.comments.data[i].friendly_time = elgg.friendly_time(ef);
				if (e.message) value.comments.data[i].message = e.message.TruncateString().ParseEverythings('facebook');
				value.comments.data[i].like = e.user_likes ? elgg.echo('deck_river:facebook:action:unlike') : elgg.echo('deck_river:facebook:action:like');
			});
			if (vcd.length > 4) {
				value.comments.dataBefore = vcdb = value.comments.data.splice(0, vcd.length-3);
				value.comments.before = elgg.echo('deck_river:facebook:show_comments', [vcdb.length]);
			}
		}

		value.rand = (Math.random()+"").replace('.','');

		if (value.type == 'swf' && (value.source = elgg.deck_river.setVideoURLToIframe(decodeURIComponent(value.link)))) value.type = 'video';

		value['type'+value.type] = true; // used for mustache
		if (value.status_type == 'created_note') {
			value.typenote = 1;
		}

		if (!value.can_comment) value.can_comment = checkAction(value.actions, 'Comment');
		if (!value.can_like) value.can_like = checkAction(value.actions, 'Like');

		if (!value.full_picture) value.full_picture = value.picture;
		if (value.full_picture) {
			if (/https?:\/\/fbexternal/.test(value.full_picture)) {
				value.full_picture = decodeURIComponent(value.full_picture.match(/(?:url|src)=(.*(?:\&|$))/)[1]);
			}
			if (/https?:\/\/fbcdn/.test(value.full_picture)) {
				value.full_picture = value.full_picture.replace(/_s\./, '_n.');
			}
			imgs.push({src: value.full_picture, id: value.id});
		}
		output += elggRiverTemplate(value);

	});

	// resize images
	$.each(imgs, function(i, e) {
		var img = new Image();

		img.src = e.src;
		img.onload = function() {
			var tw = this.width, th = this.height,
				$i = $('#'+response.columnSettings.column).find('#img'+e.id),
				$eri = $i.data('img', [tw, th]).parent();

			if (tw >= $eri.width() || tw >= 600) {
				$i.height(Math.min($eri.addClass('big').width(), '600', tw)/tw*th);
			}
			if (tw <= 1) $i.remove(); // Don' know why, but sometimes facebook return a "safe_image" with 1x1 pixels
		};
		img.onerror = function() {
			$('#'+response.columnSettings.column).find('#img'+e.id).remove();
		};

	});

	/*if (doublePass.length && response.columnSettings.type != 'stream') {
		elgg.deck_river.FBfql(response.columnSettings.token, {
			select: 'images,caption',
			from: 'photo',
			where: 'object_id IN ("'+doublePass.join('","')+'")'
		}, function(posts) {
			if (posts) {
				$.each(posts, function(i, e) {
					var data = $.grep(response.data, function(f) {return f.id == e.post_id})[0];
					if (e.attachment.media && e.attachment.media.length) {
						$.extend(data, {
							full_picture: e.attachment.media[0].src,
							type: e.attachment.media[0].type
						});
						e.attachment.href = e.attachment.media[0].href;
					}
					$.extend(data, {
						message: e.message,
						description: e.attachment.description,
						caption: e.attachment.caption,
						icon: e.attachment.icon,
						name: e.attachment.name,
						link: (/https?:\/\/fbexternal/.test(e.attachment.href)) ? decodeURIComponent(e.attachment.href.match(/url=(.*(?:\&|$))/)[1]) : e.attachment.href
					});
					response.TheColumn.find('.item-facebook-'+e.post_id).replaceWith(elggRiverTemplate(data));
				});
			}
		});
	}*/

	return $(output);
};


