<?php
/**
 * Chat CSS
 */
?>
/**
 * Live chat
 */
.minds-live-chat{
	position:fixed;
	bottom:-175px;
	left:0;
}
.minds-live-chat ul li{
	width:225px;
	float: left;
	color: #FFF;
	position:relative;
	background: rgba(255,255,255,0.9);
	cursor:pointer;
	z-index:999;
	padding:5px;
	margin:0 5px;
}
.minds-live-chat ul li.active h3{
	color:#4690D6;
}
.minds-live-chat ul li h3{
	padding:5px 0;
	width:100%;
	height:auto;
	display:block;
}
.minds-live-chat ul li .messages{
	height:125px;
	width:auto;
	padding:5px;
	overflow:scroll;
}
.minds-live-chat ul li .message{
	width:100%;
	height:auto;
	display:block;
	clear:both;
	color:#333;
}
.minds-live-chat ul li input{
	border:1px solid #CCC;
	border-radius:0;
	-webkit-border-radius:0;
	-moz-border-radius:0;
	background:#EEE;
}
.minds-live-chat ul li .toggle-chat{
	display:none;
}
.minds-live-chat ul li.toggled{
	bottom:170px;
}
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

	background-position: 0 -630px;
}  
