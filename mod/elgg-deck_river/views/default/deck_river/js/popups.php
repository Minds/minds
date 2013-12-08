/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river popups js
 *
 */

/**
 * Elgg-deck_river popups
 *
 * @return void
 */
elgg.deck_river.popups = function() {

	// livedraggable
	(function ($) {
		$.fn.liveDraggable = function (options) {
			$(this).live('mouseover', function() {
				if (!$(this).hasClass('ui-draggable')) {
					options = $.extend({
						revert: true,
						revertDuration: 0,
						appendTo: "body",
						containment: "window",
						helper: "clone",
						distance: 20,
						zIndex: 9999,
						cursor: "crosshair"
					}, options);
					$(this).draggable(options);
				}
			});
			return this;
		};
	}($));

	// tabs popups
	$('.deck-popup .elgg-tabs a, #group_activity_module .elgg-tabs a').live('click', function() {
		var popup = $(this).closest('.elgg-body'),
			tab = $(this).attr('href');

		if (popup.find($(tab)).hasClass('hidden')) {
			popup.find('.elgg-tabs li').removeClass('elgg-state-selected');
			$(this).parent('li').addClass('elgg-state-selected');
			popup.find('.elgg-body > li').addClass('hidden').filter(tab).removeClass('hidden');
		}
		if ($(tab).find('.elgg-ajax-loader').length) {
			elgg.deck_river.LoadRiver($(tab), $(tab).children('.column-header').data());
		}
		return false;
	});

	// user info popup
	$('.elgg-user-info-popup').live('click', function() {
		elgg.deck_river.userPopup($(this).attr('title'));
		return false;
	}).liveDraggable();

	// group info popup
	$('.group-info-popup').live('click', function() {
		elgg.deck_river.groupPopup($(this).attr('title'));
		return false;
	}).liveDraggable({
		connectToSortable: '.elgg-widget-instance-favorites_groups .elgg-list',
		start: function() {
			$('.elgg-widget-instance-favorites_groups > .elgg-body').addClass('ui-state-highlight');
		},
		stop: function() {
			$('.elgg-widget-instance-favorites_groups > .elgg-body').removeClass('ui-state-highlight');
		}
	});

	// hashtag info popup
	$('.hashtag-info-popup').live('click', function() {
		var hashtag = $(this).attr('title'),
			network = $(this).data('network') || 'elgg';

		elgg.deck_river.createPopup('hashtag-info-popup', elgg.echo('deck_river:hashtag-info-header', [hashtag]));
		$('#hashtag-info-popup > .elgg-body').html(Mustache.render($('#hashtag-popup-template').html(), {hashtag: hashtag.replace('#', '')}));
		$('#hashtag-info-popup .elgg-tabs .'+network).click();
		return false;
	}).liveDraggable();

	// Twitter user info popup
	$('.twitter-user-info-popup').live('click', function() {
		elgg.deck_river.twitterUserPopup($(this).attr('title'));
	}).liveDraggable();

	// Facebook user info popup
	$('.facebook-user-info-popup').live('click', function() {
		var columnRiver = $(this).closest('.column-river'),
			token = columnRiver.length ? elgg.deck_river.getColumnSettings($(this).closest('.column-river')).token : null;

		elgg.deck_river.facebookUserPopup($(this).attr('title'), token);
	}).liveDraggable();

	// Facebook page info popup
	$('.facebook-page-info-popup').live('click', function() {
		var columnRiver = $(this).closest('.column-river'),
			token = columnRiver.length ? elgg.deck_river.getColumnSettings($(this).closest('.column-river')).token : null;

		elgg.deck_river.facebookPagePopup($(this).attr('title'), token);
	}).liveDraggable();

	// video popup for Facebook
	$('.media-video-popup').live('click', function() {
		var source = $(this).data('source'),
			title = $(this).find('h4').html() || elgg.echo('video');

		elgg.deck_river.createPopup('video-popup', title);
		var vp = $('#video-popup'),
			resizeVP = function() {
				vp.find('.elgg-body').height(vp.height() - vp.find('.elgg-head').height()+7);
			};
		vp.resizable({
			handles: 'se',
			helper: 'resizable-helper',
			start: function(event, ui) {
				$('#video-popup iframe').css('pointer-events','none');
			},
			stop: function(event, ui) {
				$('#video-popup iframe').css('pointer-events','auto');
				vp.width(ui.size.width);
				resizeVP();
			}
		}).find('.elgg-body').html(
			$('<iframe>', {src: source, width: '100%', height: '100%'}))
		resizeVP();
		return false;
	});

	// media popup for Twitter
	$('.media-image-popup').live('click', function() {
		var $this = $(this),
			type = $this.data('type');

		elgg.deck_river.createPopup('twitter-media-popup', elgg.echo(type));
		if (type == 'photo') {
			var $tmp = $('#twitter-media-popup');
			$tmp.css({width: $this.data('size_width'), height: $this.data('size_height')+$tmp.find('.elgg-head').outerHeight()}).find('.elgg-body').html($('<img>', {src: $this.data('media')}));
		}
		return false;
	})

	// drag and drop linkbox
	$('.linkbox-droppable').liveDraggable();

	// share account
	$('.share-account').live('click', function() {
		elgg.deck_river.createPopup('share-account-popup', elgg.echo('deck_river:account:share_popup'));

		var $body = $('#share-account-popup .elgg-body'),
			account_guid = $(this).data('account_guid');
		elgg.post('ajax/view/deck_river/ajax_view/share_account', {
			dataType: 'html',
			data: {
				account_guid: account_guid
			},
			success: function(response) {
				$body.html(response);
				if ($.isFunction(elgg.markdown_wiki.view)) elgg.markdown_wiki.view();
				elgg.userpicker.init();
				$body.find('.elgg-button').click(function() {
					elgg.action('deck_river/network/share', {
						data: $body.find('input').serialize(),
						success: function(json) {
							if (json.status > -1) {
								var $account = $('#elgg-object-'+account_guid);
								$account.find('.shared_users_block').replaceWith(json.output.account_block);
								$account.find('.elgg-menu-item-access').html(json.output.access);
							}
							$('#share-account-popup').remove();
						}
					});
				});
			},
			error: function() {
				$body.html(elgg.echo('deck_river:ajax:erreur'));
			}
		});
	});

}
elgg.register_hook_handler('init', 'system', elgg.deck_river.popups);


/**
 * show user popup
 * @param  {[string]} user username
 */
elgg.deck_river.userPopup = function(user) {
	elgg.deck_river.createPopup('user-info-popup', elgg.echo('deck_river:user-info-header', [user]));

	var body = $('#user-info-popup > .elgg-body');
	elgg.post('ajax/view/deck_river/ajax_view/user_info', {
		dataType: 'html',
		data: {
			user: user
		},
		success: function(response) {
			body.html(response);
			if ($.isFunction(elgg.markdown_wiki.view)) elgg.markdown_wiki.view();
		},
		error: function() {
			body.html(elgg.echo('deck_river:ajax:erreur'));
		}
	});
};



/**
 * show group popup
 * @param  {[string]} group name
 */
elgg.deck_river.groupPopup = function(group) {
	elgg.deck_river.createPopup('group-info-popup', elgg.echo('deck_river:group-info-header', [group]));

	var body = $('#group-info-popup > .elgg-body');
	elgg.post('ajax/view/deck_river/ajax_view/group_info', {
		dataType: "html",
		data: {
			group: group
		},
		success: function(response) {
			body.html(response);
		},
		error: function() {
			body.html(elgg.echo('deck_river:ajax:erreur'));
		}
	});
};



/**
 * show twitter user popup
 * @param  {[string]} user screen_name
 */
elgg.deck_river.twitterUserPopup = function(user) {
	elgg.deck_river.createPopup('user-info-popup', elgg.echo('deck_river:user-info-header', [user]));

	var body = $('#user-info-popup > .elgg-body'),
		userInfo = elgg.deck_river.findUser(user, 'twitter'),
		templateRender = function(response) {
			var value = $.extend(true, {}, response); // We need cloned var because we make some changes.

			value.profile_image_url = value.profile_image_url.replace(/_normal/, '');
			if (value.description) value.description = value.description.ParseTwitterURL(value.entities.description).ParseUsername('twitter').ParseHashtag('twitter');
			if (value.url) value.url = value.url.ParseTwitterURL(value.entities.url);
			value.created_at = $.datepicker.formatDate(elgg.echo('deck_river:created_at:date_format'), new Date(value.created_at));
			body.html(Mustache.render($('#twitter-user-profile-template').html(), value));
		};

	if (elgg.isUndefined(userInfo) || elgg.isUndefined(userInfo.id)) { // Twitter feed from search api doesn't contains user info, only screen_name and image_profile
		elgg.post('ajax/view/deck_river/ajax_json/twitter_OAuth', {
			dataType: 'json',
			data: {
				params: {method: 'get_usersShow', include_entities: true, screen_name: user}
			},
			success: function(response) {
				elgg.deck_river.storeEntity(response, 'twitter');
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



/**
 * show twitter user popup
 * @param  {[string]} user screen_name
 */
elgg.deck_river.facebookUserPopup = function(user, token) {
	elgg.deck_river.createPopup('user-info-popup', elgg.echo('deck_river:info'));

	var body = $('#user-info-popup > .elgg-body'),
		userInfo = elgg.deck_river.findUser(user, 'facebook'),
		token = token || elgg.deck_river.FBgetToken(),
		templateRender = function(response) {
			var value = $.extend(true, {}, response); // We need cloned var because we make some changes.
			response.token = token;
			body.html(Mustache.render($('#facebook-user-profile-template').html(), value));
			$('#user-info-popup > .elgg-head h3').html(elgg.echo('deck_river:user-info-header', [response.name]));
		};

	if (elgg.isUndefined(userInfo)) {
		elgg.deck_river.FBfql(token, {
			select: FbUserFields,
			from: 'user',
			where: 'uid='+user
		}, function(response) {
			if (response && !response.error) {
				response = elgg.deck_river.FBformatUser(response[0]);
				elgg.deck_river.storeEntity(response, 'facebook');
				templateRender(response);
			} else {
				body.html(elgg.echo('deck_river:ajax:erreur'));
			}
		});


	} else {
		templateRender(userInfo);
	}
};



/**
 * show twitter page popup. Stacked in group-info-popup
 * @param  {[string]} user screen_name
 */
elgg.deck_river.facebookPagePopup = function(page, token) {
	elgg.deck_river.createPopup('group-info-popup', elgg.echo('deck_river:info'));

	var body = $('#group-info-popup > .elgg-body'),
		userInfo = elgg.deck_river.findUser(page, 'facebook'),
		token = token || elgg.deck_river.FBgetToken(),
		templateRender = function(response) {
			var value = $.extend(true, {}, response); // We need cloned var because we make some changes.
			body.html(Mustache.render($('#facebook-page-profile-template').html(), value));
			$('#group-info-popup > .elgg-head h3').html(elgg.echo('deck_river:user-info-header', [response.name]));
		};

	if (elgg.isUndefined(userInfo)) {
		FB.api(page, 'GET', {
				token: token,
				fields: "id,name,link,category_list,is_published,can_post,likes,location,phone,checkins,picture,cover,website,talking_about_count,about"
			}, function (response) {
			if (response && !response.error) {
				response.token = token;
				if (response.updated_time) response.updated_time = $.datepicker.formatDate(elgg.echo('deck_river:created_at:date_format'), new Date(response.updated_time));
				if (response.website) {
					if (/^www/.test(response.website)) response.website = 'http://'+response.website;
					response.website = response.website.ParseURL();
				}
				if (!response.likes) response.likes = 0;
				if (response.about) response.about = response.about.ParseURL();
				elgg.deck_river.storeEntity(response, 'facebook');
				templateRender(response);
			} else {
				body.html(elgg.echo('deck_river:ajax:erreur'));
			}
		});
	} else {
		templateRender(userInfo);
	}
};



/**
 * Elgg-deck_river plugin
 *
 * Create a new popup
 * @return void
 */
elgg.deck_river.createPopup = function(popupID, popupTitle, callback) {
	if (!popupID) return false;
	var popupTitle = popupTitle || '';

	if (!$('#'+popupID).length) {
		$('.elgg-page-body').after(
			Mustache.render($('#popup-template').html(), {popupID: popupID, popupTitle: popupTitle})
		);
		var popup = $('#'+popupID).draggable({
			handle: '.elgg-head',
			stack: '.elgg-module-popup',
			iframeFix: true,
			opacity: 0.9,
			create: function(e, ui) {
				$('.elgg-module-popup.deck-popup').css('z-index', '-=1');
				$('#'+popupID).css('z-index', 500);
			}
		});
		popup.click(function() {
			$('.deck-popup').css('z-index', '-=1');
			$(this).closest('.deck-popup').css('z-index', 500);
		});
		popup.find('.elgg-icon-push-pin').click(function() {
			$(this).closest('.deck-popup').toggleClass('pinned');
			return false;
		});
		popup.find('.elgg-icon-delete-alt').click(function() {
			popup.remove();
			$('.tipsy').remove();
			return false;
		});
	} else {
		$('#'+popupID+' > .elgg-head h3').html(popupTitle);
		$('#'+popupID+' > .elgg-body').html($('<div>', {'class': 'elgg-ajax-loader'}));
	}

	if (callback) callback();
};



/**
 * Show popup to choose facebook groups of a facebook_account object
 * @param  {[type]} account The facebook_account guid
 * @return void
 */
elgg.deck_river.getFBGroups = function(account, token, GUID) {
	elgg.deck_river.FBget(account, 'groups', token, function(rep) {
		var groups = rep.data;

		groups.sort(function(a, b) {
			return ((a.bookmark_order < b.bookmark_order) ? -1 : ((a.bookmark_order > b. bookmark_order) ? 1 : 0));
		});
		elgg.deck_river.createPopup('facebook-groups-popup', elgg.echo('deck_river:facebook:groups'));
		var $fgp = $('#facebook-groups-popup');

		$fgp.find('.elgg-body')
			.html($('<h3>', {'class': 'pbs'}).html(elgg.echo('deck_river:facebook:groups:choose')))
			.append($('<ul>').css({'overflow-y': 'scroll', height: '552px'}));
		$.each(groups, function(i,e) {
			$fgp.find('.elgg-body ul').append(
				$('<li>', {'class': 'pas link', id: e.id}).html(e.name).click(function() {
					elgg.action('deck_river/add_facebook_group', {
						data: {
							facebook_account: GUID,
							group_id : e.id
						},
						success: function(json) {
							elgg.deck_river.network_authorize(json.output);
							$fgp.find('#'+e.id).css('background-color', '#FF7777').fadeOut();
						}
					});
				})
			);
		});
	});
};



/**
 * Show popup to choose facebook pages of a facebook_account object
 * @param  {[type]} account The facebook_account guid
 * @return void
 */
elgg.deck_river.getFBPages = function(account, token, GUID) {
	elgg.deck_river.FBget(account, 'accounts', token, function(rep) {
		var pages = rep.data;

		elgg.deck_river.createPopup('facebook-pages-popup', elgg.echo('deck_river:facebook:pages')+' Facebook');
		var $fgp = $('#facebook-pages-popup');

		$fgp.find('.elgg-body')
			.html($('<h3>', {'class': 'pbs'}).html(elgg.echo('deck_river:facebook:pages:choose')))
			.append($('<ul>').css({'overflow-y': 'scroll', height: '552px'}));
		$.each(pages, function(i,e) {
			$fgp.find('.elgg-body ul').append(
				$('<li>', {'class': 'pas link', id: e.id}).html(e.name).click(function() {
					elgg.action('deck_river/add_facebook_page', {
						data: {
							facebook_account: GUID,
							page_data : e
						},
						success: function(json) {
							elgg.deck_river.network_authorize(json.output);
							$fgp.find('#'+e.id).css('background-color', '#FF7777').fadeOut();
						}
					});
				})
			);
		});
	});
};





