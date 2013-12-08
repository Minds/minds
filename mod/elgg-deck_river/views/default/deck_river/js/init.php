
/**
 *	Elgg-deck_river plugin
 *	@package elgg-deck_river
 *	@author Emmanuel Salomon @ManUtopiK
 *	@license GNU Affero General Public License, version 3 or late
 *	@link https://github.com/ManUtopiK/elgg-deck_river
 *
 *	Elgg-deck_river init js
 *
 */



/**
 * Elgg-deck_river initialization
 *
 * @return void
 */
elgg.provide('elgg.deck_river');

elgg.deck_river.init = function() {
	$(document).ready(function() {

		// hack to see if there is some facebook accounts, and asynchronious load FB SDK
		if (JSON.stringify(deckRiverSettings).match(/network":"facebook/)) {
			elgg.deck_river.initFacebook();
		}

		if (!$('.elgg-river-layout:not(.hidden)').length) {
			$('body').removeClass('fixed-deck');
		}

		elgg.deck_river.reload();

	});

	$(window).bind('resize.deck_river', function() {
		if ( $('.deck-river').length ) {
			elgg.deck_river.SetColumnsHeight();
			elgg.deck_river.SetColumnsWidth();
			elgg.deck_river.resizeRiverImages();
		}
	});
};
elgg.register_hook_handler('init', 'system', elgg.deck_river.init);



elgg.deck_river.reload = function() {
	// Ggouv use HTML5 History capacity. It's make elgg full ajax. So, we can "stack" the deck-river and go back intantanely.
	// For that, we hidden the deck, and all tabs are stored in DOM.
	// If user go to a tab not already loaded, we reload only some stuffs, and only DOM of the new tab need to be traited.
	var $erl = $('.elgg-river-layout:not(.hidden)'); // ... so, we select only visible layout.

	if (!$erl.length) {
		$('body').removeClass('fixed-deck');

		// page for single river item view, displayed in his thread
		if ($('#json-river-thread').length) {
			var rThread = $('#json-river-thread');
			$('.elgg-river.single-view').html(elgg.deck_river.elggDisplayItems($.parseJSON(rThread.text())));
			$('.single-view .item-elgg-'+rThread.data('message-id')).addClass('viewed');
		}

		// page owner river view
		if ($('#json-river-owner').length) {
			elgg.deck_river.LoadRiver($('.elgg-page .column-river'), $('#json-river-owner').data());
		}

	} else {

		$('body').addClass('fixed-deck');
		elgg.deck_river.SetColumnsHeight();
		elgg.deck_river.SetColumnsWidth();


		// make columns sortable
		$erl.find('.deck-river-lists-container').sortable({
			items: '.column-river',
			connectWith: '.deck-river-lists-container',
			handle: '.column-handle',
			forcePlaceholderSize: true,
			placeholder: 'column-placeholder',
			opacity: 0.8,
			revert: 500,
			start: function(event, ui) { $('.column-placeholder').css('width', $('.column-header').width()-3); },
			update: elgg.deck_river.MoveColumn
		});

		// load columns
		$erl.find('.column-river').each(function() {
			elgg.deck_river.LoadRiver($(this), elgg.deck_river.getColumnSettings($(this)));
		});

		// arrow to scroll deck columns
		var $drl = $erl.find('#deck-river-lists');

		$('.deck-river-scroll-arrow.left span').click(function() {
			$drl.scrollTo(Math.max(0, $drl.scrollLeft()-$(window).width()+40), 500, {easing:'easeOutQuart'});
			$erl.find('.deck-river-scroll-arrow.right span').removeClass('hidden');
		});
		$('.deck-river-scroll-arrow.right span').click(function() {
			$drl.scrollTo($drl.scrollLeft()+$(window).width()-40, 500, {easing:'easeOutQuart'});
			$erl.find('.deck-river-scroll-arrow.left span').removeClass('hidden');
		});
		$drl.unbind('scroll').scroll(function() {
			var $this = $(this),
				containerWidth = $erl.find('.deck-river-lists-container').width() - $this.width()
				arrows = $erl.find('.deck-river-scroll-arrow');

			if ($this.scrollLeft() == 0) {
				arrows.filter('.left').find('span').addClass('hidden').next().html('');
			} else if ($this.scrollLeft() > containerWidth-2) { // -2 cause scroll bar on OSX
				arrows.filter('.right').find('span').addClass('hidden').prev().html('');
			} else {
				arrows.find('span').removeClass('hidden');
			}
		});
		if ($drl.get(0).scrollWidth == $drl.get(0).clientWidth) $erl.find('.deck-river-scroll-arrow span').addClass('hidden');

		// auto scroll columns
		$erl.find('.elgg-river').bind('scroll.moreItem', function() {
			var $this = $(this),
				$rtt = $this.closest('.column-river').find('.river-to-top');

			if ($this.scrollTop()+$this.height() == $this.get(0).scrollHeight) {
				$this.find('.moreItem').click();
			}
			if ($this.scrollTop() > 0) {
				$rtt.removeClass('hidden');
			} else {
				$rtt.addClass('hidden');
			}
		});

	}

};


/**
 * Call settings of a column in popup
 *
 * Makes Ajax call to display settings of a column and perform change
 *
 * @param {TheColumn} the column
 * @return void
 */
elgg.deck_river.ColumnSettings = function(TheColumn) {
	if (!$('#column-settings').length) {
		elgg.deck_river.createPopup('column-settings', elgg.echo('deck_river:settings'), function() {
			$('#column-settings').find('.elgg-icon-push-pin').remove();
		});
	} else {
		$('#column-settings .elgg-body').html($('<div>', {'class': 'elgg-ajax-loader'}));
	}

	var columnID = TheColumn ? TheColumn.attr('id') : 'new';

	elgg.post('ajax/view/deck_river/ajax_view/column_settings', {
		dataType: "html",
		data: {
			tab: $('.elgg-river-layout:not(.hidden) #deck-river-lists').data('tab'),
			column: columnID
		},
		success: function(response) {
			var $cs = $('#column-settings');
			$cs.find('.elgg-body').html(response);

			elgg.autocomplete.init();

			// network vertical tabs
			$cs.find('.elgg-tabs.networks a').click(function() {
				var $etn = $(this).attr('class');
				$cs.find('.elgg-tabs > li').removeClass('elgg-state-selected');
				$(this).parent('li').addClass('elgg-state-selected');
				$cs.find('.tab, input.elgg-button-submit').addClass('hidden');
				$cs.find('.tab.'+$etn+', input.elgg-button-submit.'+$etn).removeClass('hidden');
				$cs.find('.column-type').trigger('change');
			});
			if ($cs.data('network')) $cs.find('.elgg-tabs.networks a.'+$cs.data('network')).click(); // used when authorize social network callback

			// account modules
			$cs.find('.in-module').change(function() {
				var network = $(this).attr('name').replace('-account', '');

				$(this).closest('.box-settings').find('.multi').addClass('hidden').filter('.' + $(this).val()).removeClass('hidden');
				$cs.find('select[name="'+network+'-lists"]').html('');
				$cs.find('.column-type').trigger('change');
			}).trigger('change');

			// dropdown
			$cs.find('.' + $cs.find('.tab:visible .column-type').val()+'-options').show();
			$cs.find('.column-type').change(function() {
				var $bs = $(this).closest('.box-settings'),
					$stl = $bs.find('select[name="twitter-lists"]'),
					network_account = $bs.find('.in-module').val();

				$bs.find('li').not(':first-child').hide();
				$bs.find('.'+$(this).val()+'-options').show();

				// Get lists for Twitter
				if ($(this).val() == 'get_listsStatuses' && !($stl.data('list_loaded') == network_account) && $stl.parent().hasClass('hidden')) {
					$bs.find('.get_listsStatuses-options div').removeClass('hidden');
					elgg.action('deck_river/twitter', {
						data: {
							twitter_account: network_account,
							method: 'get_listsList'
						},
						dataType: 'json',
						success: function(json) {
							$.each(json.output.result, function(i, e) {
								if (!$stl.find('option[value="'+e.id+'"]').length) $stl.append($('<option>').val(e.id).html(e.full_name));
							});
							$bs.find('.get_listsStatuses-options div').addClass('hidden');
							$stl.data('list_loaded', network_account);
						},
						error: function() {
							return false;
						}
					});
				}

				// Hide every item except feed for group
				if ($(this).attr('name') == 'facebook-type') {
					if ($bs.find('.elgg-module:not(.hidden) .elgg-river-timestamp').hasClass('limited')) {
						$(this).val('feed').find('option[value!="feed"]').attr('disabled','disabled');
					} else {
						$(this).find('option').removeAttr('disabled');
					}
				}
			}).trigger('change');

			$('.page-options input[name="facebook-page_name"]').autocomplete({
				html: "html",
				select: function(event, ui) {
					$(this).val(ui.item.name);
					$(this).next().val(ui.item.value);
					return false;
				},
				source: function(request, response) {
					FB.api('search', 'GET', {
						access_token: elgg.deck_river.FBgetToken(),
						q: request.term,
						limit: 30,
						type: 'page'
					},
						function (rep) {
							if (rep && !rep.error) {
								var items = [];
								$.each(rep.data, function(i,e) {
									e.value = e.id;
									items.push(e);
								});
								response(items);
							}
						}
					);
				},
				autoFocus: true
			}).data('autocomplete')._renderItem = function( ul, item) {
				return $('<li>').data('item.autocomplete', item)
					.append($('<a>')[ this.options.html ? "html" : "text" ](
						'<div class="elgg-image-block elgg-autocomplete-item clearfix"><div class="elgg-image"><img src="https://graph.facebook.com/'+
						item.value+'/picture?width=25&height=25" width="25" height="25"></div><div class="elgg-body"><h3>'+item.name+'</h3></div></div>'))
					.appendTo(ul);
			};

			$('.elgg-foot .elgg-button').click(function() {
				var submitType = $(this).attr('name'),
					$drfcs = $(this).closest('.deck-river-form-column-settings');

				if (submitType == 'delete' && !confirm(elgg.echo('deck-river:delete:column:confirm'))) return false;

				elgg.action('deck_river/column/settings', {
					data: $drfcs.serialize() + '&submit=' + submitType + '&twitter_list_name=' + $drfcs.find('select[name="twitter-lists"] option:selected').text(),
					dataType: 'json',
					success: function(json) {
						var response = json.output;

						if (response) {
							deckRiverSettings = response.deck_river_settings;
							if (columnID == 'new') {
								var $erl = $('.elgg-river-layout:not(.hidden)');
								$erl.find('.nofeed').remove();
								$erl.find('.deck-river-lists-container').append(Mustache.render($('#column-template').html(), response));
								elgg.deck_river.SetColumnsHeight();
								elgg.deck_river.SetColumnsWidth();
								elgg.deck_river.resizeRiverImages();
								$erl.find('#deck-river-lists').animate({scrollLeft: $(this).width()});
							}

							var TheColumn = $('.elgg-river-layout:not(.hidden) #'+response.column); // redeclare because maybe it was just created.

							if (submitType == 'delete' && response.action == 'delete') {
								TheColumn.find('*').css('background-color', '#FF7777');
								TheColumn.fadeOut(400, function() {
									$(this).animate({'width':0},'', function() {
										$(this).remove();
										elgg.deck_river.SetColumnsWidth();
										elgg.deck_river.resizeRiverImages();
									});
								});
								$cs.remove();
								return false;
							}

							elgg.deck_river.SetColumnsHeight();
							TheColumn.find('.elgg-list').html($('<div>', {'class': 'elgg-ajax-loader'}));
							TheColumn.find('.column-filter').remove();
							TheColumn.find('.column-header').replaceWith(response.header);

							elgg.deck_river.LoadRiver(TheColumn, elgg.deck_river.getColumnSettings(TheColumn));

							$cs.remove();
						}
					},
					error: function() {
						return false;
					}
				});
				return false;
			});
		}
	});
};



/**
 * Called by twitter and facebook callback
 *
 * Add new account in non-pinned network and reload the column-settings if open
 *
 * @param {token} false if network error, else it contain the account view
 * @return void
 */
elgg.deck_river.network_authorize = function(token) {
	var p = window.opener || window; // function called from a popup window on from main window

	if (token == false) {
		$.each(authorizeError, function(i, e) {
			p.elgg.register_error(e);
		})
		window.close();
	} else {
		var tn = token.network;
		// reload column settings popup if it's open
		if (p.$('#column-settings').length) {
			var c = p.$('#'+p.$('#column-settings input[name="column"]').val());
			p.$('#column-settings').data('network', tn);
			if (c.length == 1) {
				c.find('.elgg-column-edit-button').click();
			} else {
				p.$('.elgg-add-new-column').click();
			}
		}

		// add new network in applications page
		if (p.$('.elgg-module-'+tn).length) {
			var $em = p.$('.elgg-module-'+tn);
			$em.find('.elgg-module-featured').replaceWith($('<ul>', {'class': 'elgg-list elgg-list-entity'}));
			$em.find('.elgg-list').prepend(token.full).children().first().effect("highlight", {}, 3000);
		}

		// remove add-network popup
		p.$('#add_social_network').remove();

		// add new network account in #thewire-network
		p.$('#thewire-network .non-pinned .net-profiles').prepend(token.network_box);
		p.elgg.thewire.move_account();

		// execute code
		p.eval(token.code);

		// show message
		p.elgg.system_message(p.elgg.echo('deck_river:'+tn+':authorize:success'));
		window.close();
	}
}



/**
 * Persist the column's new position
 *
 * @param {Object} event
 * @param {Object} ui
 *
 * @return void
 */
elgg.deck_river.MoveColumn = function(event, ui) {

	elgg.action('deck_river/column/move', {
		data: {
			tab: ui.item.closest('.elgg-river-layout:not(.hidden) #deck-river-lists').data('tab'),
			column: ui.item.attr('id'),
			position: ui.item.index()
		}
	});

	// @hack fixes jquery-ui/opera bug where draggable elements jump
	ui.item.css('top', 0);
	ui.item.css('left', 0);
};


/**
 * Resize columns height
 */
elgg.deck_river.SetColumnsHeight = function() {
	function scrollbarWidth() {
		if (!$._scrollbarWidth) {
			var $body = $('body'),
				w = $body.css('overflow', 'hidden').width();
			$body.css('overflow','scroll');
			w -= $body.width();
			if (!w) w=$body.width()-$body[0].clientWidth; // IE in standards mode
			$body.css('overflow','');
			$._scrollbarWidth = w+1;
		}
		return $._scrollbarWidth;
	}
	var $erl = $('.elgg-river-layout:not(.hidden)'),
		$drl = $erl.find('#deck-river-lists'),
		oT = $drl.offset().top,
		wH = $(window).height(),
		H = wH - oT - scrollbarWidth();
	$erl.find('.column-river').height(H);
	$erl.find('.elgg-river').height(H - $('.column-header').height());
	$drl.height(wH - oT);
};


/**
 * Resize columns width
 */
elgg.deck_river.SetColumnsWidth = function() {
	var $erl = $('.elgg-river-layout:not(.hidden)'),
		WindowWidth = $erl.find('#deck-river-lists').width(),
		CountLists = $erl.find('.column-river').length,
		ListWidth = 0,
		i = 0;

	while ( ListWidth < deck_river_min_width_column ) {
		ListWidth = (WindowWidth) / ( CountLists - i );
		i++;
	}
	$erl.find('.column-river, .column-placeholder').width(ListWidth - 2);
	$erl.find('.deck-river-lists-container').removeClass('hidden').width(ListWidth * CountLists);
};





