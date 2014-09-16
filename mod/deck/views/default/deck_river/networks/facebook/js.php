<?php if (0): ?><script><?php endif; ?>
/**
 * Facebook display
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
		
	$.each(response.results, function(key, value) {
		//output += elggRiverTemplate(value);
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
				/*var st = value.story_tags[Object.keys(value.story_tags)[Object.keys(value.story_tags).length - 1]][0];
				value.via = {id: st.id, name: st.name};
				if (st.type == 'page') value.via.category = 1;*/
			} else if (/updated his cover photo/.test(value.story)) {
				value.summary = elgg.echo('deck_river:facebook:summary:updated_cover_photo');
			} else if (/'s photo/.test(value.story)) {
				if (/Timeline Photos?/.test(value.name)) {
					delete value.name;
					//show a large image
					if (/https?:\/\/fbcdn/.test(value.full_picture)) 
						value.full_picture = value.full_picture.replace(/_s\./, '_n.');
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

		//get larger photos
		if(value.picture){
			var index;
			var options = ["_s.jpg","_t.jpg"];
			var pic = value.picture;
			for (index = 0; index < options.length; ++index) {
 				
				if(pic.indexOf(options[index]) != -1){
					value.picture = pic.replace(options[index],"_n.jpg");
				}
			}
		}

		if (value.likes) {
			var vld = value.likes.data,
				count = parseInt(value.likes.count) || vld.length,
				u = '';

			value.likes.string = elgg.echo('deck_river:facebook:like'+(count == 1 ? '':'s'), [count]);
			$.each(vld, function(i, e) {
				u += ','+e.id;
				//if (response.columnSettings && e.id == response.columnSettings.user_id) value.liked = true;
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

		if (!value.full_picture) 
			value.full_picture = value.picture;
			
		if (value.full_picture) {
			if (/https?:\/\/fbexternal/.test(value.full_picture)) {
				value.full_picture = decodeURIComponent(value.full_picture.match(/(?:url|src)=(.*(?:\&|$))/)[1]);
			}
			//show a larger image, if we can get one
			if (/https?:\/\/fbcdn/.test(value.full_picture)) {
				value.full_picture = value.full_picture.replace(/_s\./, '_n.');	
			}
			
			//pass through our proxy so that we don't have issues with https
			value.full_picture = '<?= elgg_get_site_url(); ?>thumbProxy?src=' + encodeURIComponent(value.full_picture);
			
			imgs.push({src: value.full_picture, id: value.id});
		}
		output += elggRiverTemplate(value);
		
	});

	/*if (response.columnSettings.type == 'stream') {
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

// Get url of networks to authorize user
$(document).on('click', '#authorize-facebook', function(e){

	elgg.deck_river.initFacebook(); // we load facebook before authorize to prevent error on column load.
	window.open(elgg.get_site_url() + 'authorize/facebook', 'ConnectWithOAuth', 'location=0,status=0,width=1200,height=500');
	
	return false;
});

/**
 * Facebook like
 * @return {[type]} [description]
 */
// like post
$(document).on('click', '.elgg-menu-item-like a', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$column_guid = $cl.attr('id'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('object_id') || $eli.data('id');

	/**
	 * Trigger the gerneric action handler, actions/network/action
	 */
	elgg.action('deck_river/network/action', {
		data: {id: id, column_guid:$column_guid, method: 'like'},
		dataType: 'json',
		success: function(json) {
			$(this).addClass('liked');
		}
	});	
});
// like comment
$(document).on('click', '.comment-item-like', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$column_guid = $cl.attr('id'),
		$eli = $this.closest('.elgg-item'),
		id = $eli.attr('id'),
		unlike = $this.hasClass('unlike');

	/**
	 * Trigger the gerneric action handler, actions/network/action
	 */
	elgg.action('deck_river/network/action', {
		data: {id: id, column_guid:$column_guid, method: unlike ? 'unlike' : 'like'},
		dataType: 'json',
		success: function(json) {
			if(unlike){
				$this.removeClass('unlike')
				$this.text('Like');
			} else {
				$this.addClass('unlike').addClass('liked');
				$this.text('Un-like');
			}
		}
	});	
});


/**
 * Comment facebook object
 * @return {[type]} [description]
 */
$(document).on('click', '.facebook-comment-form .elgg-button', function() {
	var $this = $(this),
		settings = elgg.deck_river.getColumnSettings($this.closest('.column-river')),
		$txt = $this.prev('textarea');

	console.log(settings);
	return;

	elgg.deck_river.FBpost($this.closest('.elgg-list-item').data('object_guid').split('_')[1], 'comments', {
		message: $this.prev('textarea').val(),
		access_token: settings.token
	}, function(response) {
		if (response.id) {
			var date = Date(),
				dateF = date.FormatDate(),
				data = {
					id: response.id,
					from: {
						name: settings.username,
						id: settings.user_id
					},
					created_time: date,
					posted: dateF,
					friendly_time: elgg.friendly_time(dateF),
					message: $txt.val(),
					like: elgg.echo('deck_river:facebook:action:like')
				};
			$this.closest('.elgg-body').find('.elgg-list-comments').append(Mustache.render($('#erFBt-comment').html(), data));
			$txt.val('');
		} else {
			elgg.deck_river.FBregister_error(response.error.code);
		}
	});
});



<?php if (0): ?></script><?php endif; ?>

