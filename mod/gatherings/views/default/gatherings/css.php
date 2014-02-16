<?php
/**
 * Chat CSS
 */
?>
/**
 * Gatherings
 */
/**
 * Gathering view page
 */
.gathering{
	width:100%;
	margin:5%;
}

.gathering > .chat{
	width:25%;
	height:200px;
	position:absolute;
}

.gathering > .chat > .messages{	
	width:100%;
	height:200px;
	display:block;
	overflow: scroll;
	padding:8px;
	border:1px solid #eee;
}

.gathering > .chat > .message{
	padding:8px 0;
	width:100%;
	height:auto;
	display:block;
	clear:both;
	color:#333;
}

.gathering > .video{
	float:right;
	width:50%;
	height:200px;
}

/**
 * Live chat
 */
.minds-live-chat{
	position:fixed;
	bottom:-200px;
	left:0;
	z-index:900;
}
.minds-live-chat ul li{
	position:absolute;
	width:225px;
	height:245px;
	
	bottom:0;
	
	color: #FFF;
	background:#EEE;
	border:1px solid #DDD;
	cursor:pointer;
	z-index:999;
	margin:0 5px;
}
.minds-live-chat ul li.active{
	 background: rgba(70,144,214,0.9);
}
.minds-live-chat ul li.active.toggled{
	background: rgba(255,255,255,0.9);
	color:#4690D6;
}
.minds-live-chat ul li .avatar{
	float:left;
	width:25px;
	margin:8px;
}
.minds-live-chat ul li h3{
	width:100%;
	margin:8px;
	height:auto;
	display:block;
	font-size:14px;
}
.minds-live-chat ul li .del{
	position: absolute;
	top: 0;
	right: 0;
	padding: 10px;
	color:#888;
}
.minds-live-chat ul li .messages{
	height:146px;
	width:auto;
	background:#F8F8F8;
	padding:8px;
	overflow:scroll;
}
.minds-live-chat ul li .message{
	width:100%;
	height:auto;
	display:block;
	clear:both;
	color:#333;
	padding:4px 0;
}
.minds-live-chat ul li .message .user_name{
	font-weight:bold;
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
	bottom:200px;
}
.minds-live-chat ul li.userlist{
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
