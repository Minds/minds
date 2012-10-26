<?php
/**
 * Chat JavaScript extension for elgg.js
 */
?>
elgg.provide('elgg.chat');

elgg.chat.init = function() {
	if (elgg.is_logged_in()) {
	
//		elgg.chat.getMessages();
	}
};

/**
 * Change the color of new messages.
 */
elgg.chat.markMessageRead = function() {
	var activeMessages = $('.elgg-chat-messages .elgg-chat-unread');
	var message = $(activeMessages[0]);
	message.animate({backgroundColor: '#ffffff'}, 1000).removeClass('elgg-chat-unread');
};

/**
 * Get messages via AJAX.
 * 
 * Get both the number of unread messages and a preview list
 * of the latest messages. Then populate the topbar menu item
 * and popup module with the data.
 */
elgg.chat.getMessages = function() {
	var url = elgg.normalize_url("mod/chat/notifier.php");
	var messages = elgg.getJSON(
		url,
		{
			success: function(data) {
				// Add notifier to topbar menu item
				var counter = $('#chat-preview-link > .messages-new');
				
				counter.text(data.count)
				if (data.count > 0) {
					counter.removeClass('hidden');
				} else {
					counter.addClass('hidden');
				}

				// Add content to popup module
				$('#chat-messages-preview > .elgg-body > ul').replaceWith(data.preview);
				
				if ($('#chat-messages-preview > .elgg-body > ul').text()) {
					$('#chat-messages-none').hide();
					$('#chat-view-all').show();
				} else {
					$('#chat-messages-none').show();
					$('#chat-view-all').hide();
				}
			}
		}
	);
}

/**
 * Repositions the chat members popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.chatMemberPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-chat-members')) {
		options.my = 'left bottom';
		options.at = 'right top';
		return options;
	}
	return null;
};

/**
 * Repositions the chat messages popup
 *
 * @param {String} hook    'getOptions'
 * @param {String} type    'ui.popup'
 * @param {Object} params  An array of info about the target and source.
 * @param {Object} options Options to pass to
 *
 * @return {Object}
 */
elgg.ui.chatMessagesPopupHandler = function(hook, type, params, options) {
	if (params.target.hasClass('elgg-chat-messages-preview')) {
		options.my = 'left top';
		options.at = 'left bottom';
		return options;
	}
	return null;
};

elgg.register_hook_handler('init', 'system', elgg.chat.init);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.chatMemberPopupHandler);
elgg.register_hook_handler('getOptions', 'ui.popup', elgg.ui.chatMessagesPopupHandler);
