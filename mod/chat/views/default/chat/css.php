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
	z-index:900;
}
.minds-live-chat ul li{
	position:absolute;
	width:225px;
	height:200px;
	
	bottom:0;
	
	color: #FFF;
	background: rgba(255,255,255,0.9);
	cursor:pointer;
	z-index:999;
	padding:5px;
	margin:0 5px;
}
.minds-live-chat ul li.active{
	 background: rgba(70,144,214,0.9);
}
.minds-live-chat ul li.active.toggled{
	background: rgba(255,255,255,0.9);
	color:#4690D6;
}
.minds-live-chat ul li h3{
	padding:5px 0;
	width:100%;
	height:auto;
	display:block;
}
.minds-live-chat ul li .del{
	position: absolute;
	top: 0;
	right: 0;
	padding: 10px;
	color:#888;
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
	overflow:scroll;
}
.minds-live-chat ul li.userlist{
	height:200px;
	width:200px;
}
.minds-live-chat ul li.userlist.toggled{
	bottom:170px;
}
.minds-live-chat ul li.userlist span{
	color:#333;
}
.minds-live-chat ul li.userlist ul li{
	width:100%;
	height:auto;
	float:left;
	position:relative;
}
.minds-live-chat ul li.userlist ul li h3{
	font-size:12px;
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
