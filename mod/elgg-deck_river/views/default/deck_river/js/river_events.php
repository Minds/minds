/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river river events js
 *
 *	All events in river feed triggered by users
 */

// refresh column
$('.elgg-column-refresh-button').live('click', function() {
	var TheColumn = $(this).closest('.column-river');
	elgg.deck_river.RefreshColumn(TheColumn, elgg.deck_river.getColumnSettings(TheColumn));
});

// refresh all columns
$('.elgg-refresh-all-button').live('click', function() {
	var $erl = $('.elgg-river-layout:not(.hidden) ');
	$erl.find('.deck-river-scroll-arrow div').html('');
	$erl.find('.elgg-column-refresh-button').each(function() {
		$(this).click();
	});
});

// load more in column
$('.moreItem').live('click', function() {
	var TheColumn = $(this).closest('.column-river'),
		settings = elgg.deck_river.getColumnSettings(TheColumn);

	if (settings) {
		elgg.deck_river.LoadMore(TheColumn, settings);
	} else {
		elgg.deck_river.getFilters(TheColumn, TheColumn.children('.column-header').data(), function(data) {
			elgg.deck_river.LoadMore(TheColumn, data);
		});
	}
});

// hide alert or other messages in column
$('.column-messages').live('click', function() {
	$(this).stop(true, true).toggle('slide',{direction: 'up'}, 300, function() {$(this).html('')});
});

// Scroll column to top
$('.river-to-top').live('click', function() {
	$(this).closest('.column-river').find('.elgg-river').scrollTo(0, 500, {easing:'easeOutQuart'});
});

/**
 * Column filter
 */
$('.elgg-column-filter-button, .column-header .filtered, .column-filter .close-filter').live('click', function() {
	var $cr = $(this).closest('.column-river'),
		$cf = $cr.find('.column-filter').toggleClass('elgg-state-active'),
		$er = $cr.find('.elgg-river');

	if ($cf.hasClass('elgg-state-active')) {
		var cfH = $cf.height('auto').height();
		$cf.css({height: 0, display: 'block'}).stop(true, true).animate({height: cfH}, 200);
		$er.stop(true, true).animate({height: '-='+(cfH+15)});
	} else {
		var cfH = $cf.height();
		$cf.stop(true, true).animate({height: 0}, 200, function() {
			$(this).css({display: 'none'});
		});
		$er.stop(true, true).animate({height: '+='+(cfH+15)});
	}
	return false;
});
$('.column-filter .elgg-input-checkbox').live('click', function() {
	var $cf = $(this).closest('.column-filter');
	if ( $(this).val() == 'All' ) {
		$cf.find('.elgg-input-checkbox').not($(this)).prop('checked', $(this).is(':checked'));
	} else {
		$cf.find('.elgg-input-checkbox[value="All"]').prop('checked', false);
	}
});
$('.column-filter .elgg-button-submit').live('click', function() {
	var TheColumn = $(this).closest('.column-river'),
		loader = '<div class="elgg-ajax-loader"></div>';

	if ($(this).closest('.deck-popup').length || $(this).closest('#group_activity_module').length) { // filter in popup
		elgg.deck_river.getFilters(TheColumn, TheColumn.find('.column-header').data(), function(data) {
			TheColumn.find('.elgg-river').html(loader);
			elgg.deck_river.LoadRiver(TheColumn, data);
		});
	} else { // filter in column
		elgg.deck_river.getFilters(TheColumn, elgg.deck_river.getColumnSettings(TheColumn), function(columnSettings) {
			// make call
			if (columnSettings.direct == false) delete columnSettings.direct;
			elgg.deck_river.setColumnSettings(TheColumn, columnSettings);
			var clonedSettings = $.extend(true, {}, columnSettings);

			clonedSettings.save_settings = columnSettings; // we tell we want to save settings
			delete clonedSettings.save_settings.tab;
			delete clonedSettings.save_settings.column;
			delete columnSettings;
			TheColumn.find('.elgg-river').html(loader);
			elgg.deck_river.LoadRiver(TheColumn, clonedSettings);
		});

	}
	return false;
});

// Open popup to add an network account
$('.add_social_network').live('click', function() {
	elgg.deck_river.createPopup('add_social_network', elgg.echo('deck-river:add:network'), function() {
		$('#add_social_network').find('.elgg-icon-push-pin').remove();
	});
	elgg.post('ajax/view/deck_river/ajax_view/add_social_network', {
		dataType: "html",
		success: function(response) {
			$('#add_social_network').find('.elgg-body').html(response);
		}
	});
});

// Get url of networks to authorize user
$('#authorize-twitter, #authorize-facebook').live('click', function(){
	var network = $(this).attr('id').replace('authorize-', '');

	if (network == 'facebook') elgg.deck_river.initFacebook(); // we load facebook before authorize to prevent error on column load.
	elgg.action('deck_river/network/getLoginUrl', {
		data: {
			network: network
		},
		success: function(json) {
			window.open(json.output, 'ConnectWithOAuth', 'location=0,status=0,width=800,height=400');
		}
	});
	return false;
});

// Column settings
$('.elgg-column-edit-button').live('click', function() {
	elgg.deck_river.ColumnSettings($(this).closest('.column-river'));
});

// Delete tabs
$('.delete-tab').live('click', function() {
	var tab = $(this).closest('li').text();
	var tab_string = tab.charAt(0).toUpperCase() + tab.slice(1);
	if (confirm(elgg.echo('deck_river:delete:tab:confirm', [tab_string]))) {
		// delete tab through ajax
		elgg.action('deck_river/tab/delete', {
			data: {
				tab: tab
			},
			success: function(response) {
				if (response.status == 0 ) {
					deckRiverSettings = response.output;
					$('li.elgg-menu-item-'+tab).remove();
				}
			}
		});
	}
	return false;
});


// Add new column
$('.elgg-add-new-column').live('click', function() {
	if ($('.elgg-river-layout:not(.hidden) .column-river').length >= deck_river_max_nbr_columns) {
		elgg.system_message(elgg.echo('deck_river:limitColumnReached'));
	} else {
		elgg.deck_river.ColumnSettings();
	}
});


// rename column button
$('.elgg-form-deck-river-tab-rename .elgg-button-submit').live('click', function() {
	elgg.action('deck_river/tab/rename', {
		data: $(this).closest('.elgg-form').serialize(),
		success: function(json) {
			if (json.status != -1) {
				deckRiverSettings = json.output.user_river_settings;
				$('#deck-river-lists').data('tab', json.output.tab_name);
				$('.elgg-menu-item-'+json.output.old_tab_name+' a').text(json.output.tab_name.charAt(0).toUpperCase() + json.output.tab_name.slice(1));
				$('.elgg-menu-item-'+json.output.old_tab_name).removeClass('elgg-menu-item-'+json.output.old_tab_name).addClass('elgg-menu-item-'+json.output.tab_name);
			}
		}
	});
	$('body').click();
	return false;
});






// load discussion
$('.elgg-river-responses a.thread').live('click', function() {
	elgg.deck_river.LoadDiscussion($(this));
});



/**
 * Reply to a message
 * @return {[type]} [description]
 */
$('.elgg-menu-item-response a').live('click', function() {
	var item = $(this).closest('.elgg-list-item');
	elgg.deck_river.responseToWire(item, '@' + item.data('username') + ' ');
});

$('.elgg-menu-item-response-all a').live('click', function() {
	var item = $(this).closest('.elgg-list-item'),
		match_users = item.find('.elgg-river-message').first().text().match(/(?:\s|^)@\w{1,}/g);

	match_users = $.grep(match_users, function(val, i) { // don't mention himself
		return $.trim(val) != '@'+elgg.session.user.username;
	});
	match_users = '@'+item.data('username') + ' ' + $.grep(match_users, function(val, i) { // Prepend the username of the item river owner
		return $.trim(val) != '@'+item.data('username');
	}).join('') + ' ';
	elgg.deck_river.responseToWire(item, match_users);
});



/**
 * Add response of a wire message to thewire form
 * @param  {[type]} riverItem [description]
 * @param  {[type]} message   [description]
 * @return {[type]}           [description]
 */
elgg.deck_river.responseToWire = function(riverItem, message) {
	var network = elgg.deck_river.getColumnSettings(riverItem.closest('.column-river')).network || 'elgg',
		id = riverItem.attr('data-object_guid') || riverItem.attr('data-id');

	$('.elgg-list-item').removeClass('responseAt');
	$('.item-'+network+'-'+riverItem.attr('data-id')).addClass('responseAt');
	$('#thewire-header').find('.responseTo')
		.removeClass('hidden')
		.html(elgg.echo('responseToHelper:text:'+network, [riverItem.data('username'), riverItem.find('.elgg-river-message').first().text()]))
		.attr('title', elgg.echo('responseToHelper:delete:'+network, [riverItem.data('username')]))
	.next('.parent').val(id).attr('name', network+'_parent');
	$('#thewire-textarea').val(message).focus().keydown();
};



/**
 * Share menu, used in elgg objects except thewire object
 * @param  {[type]} e [description]
 * @return {[type]}   [description]
 */
$('.elgg-menu-item-share a').live('click', function(e) {
	var $this = $(this),
		thisPos = $this.offset();

	if ($('.share-menu').length) $('.share-menu').remove();
	$this.addClass('elgg-state-active');
	if ($this.closest('.elgg-menu-river').length) {
		var $parent = $this.closest('.elgg-list-item'),
		top = 25,
		left = $parent.width()-178,
		sl = site_shorturl + AlphabeticID.encode($parent.data('object_guid')),
		text = $parent.find('.elgg-river-object').text();
	} else {
		var $parent = $('.elgg-page-body'),
		top = thisPos.top+22,
		left = ($('.elgg-page-topbar').length ? 0 : 40) + thisPos.left-198,
		sl = $this.attr('href'),
		text = $this.data('title');
	}
	$parent.append(
		$(Mustache.render($('#share-menu').html(), {
			sl: sl,
			text: text,
			logged_in: elgg.is_logged_in()
		})).css({top: top, left: left})
	);

	$('.share-menu').add($this.closest('.elgg-list-item')).mouseleave(function() {
		$this.removeClass('elgg-state-active');
		$('.share-menu').remove();
		$(document).unbind('click.sharemenu');
	});

	$(document).unbind('click.sharemenu').bind('click.sharemenu', function() {
		$this.removeClass('elgg-state-active');
		$('.share-menu').remove();
		$(document).unbind('click.sharemenu');
	});
	return false;
});



/**
 * Retweet used in Twitter feed or Facebook feed
 * @return {[type]} [description]
 */
$('.elgg-menu-item-retweet a').live('click', function() {
	var $eli = $(this).closest('.elgg-list-item'),
		columnSettings = elgg.deck_river.getColumnSettings($(this).closest('.column-river'));

	if (columnSettings.network == 'facebook') {
		var data = $eli.find('.elgg-river-image').data();

		data = $.extend({
			mainimage: '',
			url: false,
			description: '',
			tilte: false
		}, data);
		//data.editable = false;

		$('#linkbox').removeClass('hidden').html(Mustache.render($('#linkbox-template').html(), data));
		linkParsed = data.url.replace(/\/$/, '');
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
$('a[twitter_action]').live('click', function(e) {
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


/**
 * Facebook like
 * @return {[type]} [description]
 */
// like post
$('.elgg-menu-item-like a').live('click', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('object_id') || $eli.data('id');

	elgg.deck_river.FBpost(id, 'likes', {
		access_token: elgg.deck_river.getColumnSettings($cl).token
	}, function(response) {
		if (response === true) {
			elgg.system_message(elgg.echo('deck_river:facebook:liked'));
			var $lp = $eli.find('.likes-popup');
			if ($lp.length) {
				$lp.html(elgg.echo('deck_river:facebook:likes', [parseInt($lp.html().match(/\d+/))+1]))
					.prev().addClass('liked');
			} else {
				$eli.find('.elgg-river-responses.sharelike-count').append('<span class="elgg-icon elgg-icon-thumbs-up-alt float gwfb prs liked"></span><span class="float likes-popup prm">'+elgg.echo('deck_river:facebook:like', [1])+'</span>');
			}
		} else {
			elgg.deck_river.FBregister_error(response.error.code);
		}
	});
});
// like comment
$('.comment-item-like').live('click', function() {
	var $this = $(this),
		$cl = $this.closest('.column-river'),
		$eli = $this.closest('.elgg-list-item'),
		id = $eli.data('object_id') || $eli.data('id'),
		unlike = $this.hasClass('unlike');

	elgg.deck_river[(unlike  ? 'FBdelete' : 'FBpost')](id.split('_')[0]+'_'+$this.closest('.elgg-item').attr('id'), 'likes', {
		access_token: elgg.deck_river.getColumnSettings($cl).token
	}, function(response) {
		if (response === true) {
			$this.toggleClass('unlike').html(elgg.echo('deck_river:facebook:action:'+(unlike ? '' : 'un')+'like'));
			if ($this.next().length) {
				var $tnc = $this.nextAll('.counter'),
					count = parseInt($tnc.html()) + (unlike ? -1 : 1);

				if (count == 0) {
					$this.nextAll('span').remove();
				} else {
					$tnc.html(count);
					$this.nextAll('.elgg-icon').addClass('liked');
				}
			} else {
				$this.after('<span>&bull;</span><span class="elgg-icon elgg-icon-thumbs-up liked"></span><span class="counter">1</span>');
			}
		} else {
			elgg.deck_river.FBregister_error(response.error.code);
		}
	});
});


/**
 * Comment facebook object
 * @return {[type]} [description]
 */
$('.facebook-comment-form .elgg-button').live('click', function() {
	var $this = $(this),
		settings = elgg.deck_river.getColumnSettings($this.closest('.column-river')),
		$txt = $this.prev('textarea');

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






/**
 * Helpers
 */




/**
 * Helper to get filters of a column or popup
 * @param  {elem}     TheColumn TheColumn will be filtered
 * @param  {object}   filters   An object which filters data will be added
 * @param  {Function} callback  Function to execute after got filters
 * @return void
 */
elgg.deck_river.getFilters = function(TheColumn, filters, callback) {
	var $f = TheColumn.find('.filtered'),
		fState = $f.hasClass('hidden'),
		count = 0;

	filters.types_filter = [];
	filters.subtypes_filter = [];

	$.each(TheColumn.find('.column-filter .types:checkbox:checked'), function() {
		filters.types_filter.push(this.value);
		if (this.value == 'All') return false;
		$f.removeClass('hidden');
	});
	count += filters.types_filter.length;

	if (filters.types_filter[0] != 'All') {
		$.each(TheColumn.find('.column-filter .subtypes:checkbox:checked'), function() {
			filters.subtypes_filter.push(this.value);
		});
		$f.removeClass('hidden');
	} else {
		$f.addClass('hidden');
		delete filters.types_filter;
	}
	count += filters.subtypes_filter.length;

	if (count == 0) {
		elgg.deck_river.column_error(elgg.echo('deck_river:error:no_filter'), TheColumn.find('.column-header'), 2000);
		fState ? $f.addClass('hidden') : $f.removeClass('hidden');
	} else {
		callback(filters);
	}
};



// dropdown menu
$('.elgg-submenu').live('click', function() {
	var $this = $(this);

	$this.addClass('elgg-state-active');
	if (!$this.hasClass('elgg-button-dropdown')) {
		$this.find('.elgg-module-popup').add($this.closest('.elgg-list-item')).mouseleave(function() {
			$this.removeClass('elgg-state-active');
			$(document).unbind('click.submenu');
		});
	} else if ($this.hasClass('invert')) {
		var m = $this.find('.elgg-menu');
		m.css('top', - m.height() -5);
	}
	$(document).unbind('click.submenu').bind('click.submenu', function() {
		$this.removeClass('elgg-state-active');
		$(document).unbind('click.submenu');
	});
});


