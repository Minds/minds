<?php
/**
 * Chat CSS
 */
?>

/* ***************************************
	Chat
*************************************** */

/* Style the popup modules */
.elgg-chat-members,
#chat-messages-preview {
	width: 345px;
	position: absolute;
}

.elgg-chat-messages {
	max-height: 400px;
	overflow: auto;
}

.elgg-chat-unread {
	background: #EDF5FF;
}

.message.notifier {
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/chat/graphics/mail.png) no-repeat left;
	width: 16px;
	height: 16px;
	margin: 1px 2px;
    display:block;
}
.message.notifier:hover, .message.notifier.new{
	background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat left;

	background-position: 0 -629px;
}  
