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
	margin:0 5%;
}

/*.gathering > .chat{
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
}*/
.gathering video{
	float:left;
	width:300px;
	border-radius:2px;
	box-shadow:0 0 16px #888;
	border:1px solid #888;
	margin:8px;
}
.gathering video.talking{
	box-shadow:0 0 3px #4690D6;
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
.minds-live-chat ul li .del.entypo{
	position: absolute;
	top: 5px;
	right: 0;
	padding: 10px;
	color:#888;
}
.minds-live-chat ul li .video.entypo{
	position: absolute;
	top: 5px;	
	right: 33px;
	padding: 10px;
	color: #888;
}
.minds-live-chat ul li .messages{
	height:152px;
	width:auto;
	background:#F8F8F8;
	padding:8px;
	overflow:hidden;
	overflow-y:scroll;
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
.minds-live-chat ul li .rt-stats{
	background: #F8F8F8;
	color: #888;
	font-weight: lighter;
	padding: 0 8px;
	font-size: 11px;
	text-align: right;
	position: absolute;
	right: 0;
	bottom: 35px;
}
.minds-live-chat .call{
	position:relative;
}
.minds-live-chat .call video{
	display:none;
}
.minds-live-chat .call .flash_obj{
	width:100%;
	height:150px;
}
.minds-live-chat .call .local.active{
	position: absolute;
	bottom: 8px;
	height: 40px;
	right: 8px;
	margin: 0;
	padding: 0;
	border:1px solid #EEE;
}
.minds-live-chat .call .remote, .minds-live-chat .call .local{
	height:168px;
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
.minds-live-chat ul li.toggled .onCall{
	bottom:400px;	
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
