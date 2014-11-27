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
	overflow:hidden;
	overflow-y: scroll;
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
	margin:12px;
}
.minds-live-chat ul li h3{
	width:100%;
	margin:12px;
	height:auto;
	display:block;
	font-size:14px;
}

.minds-live-chat ul li h3 .sound{
	float:right;
	margin-right:26px;
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
	display:none;
}
.minds-live-chat ul li .messages{
	height:140px;
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

/**
 * Status messages (and errors)
 */
.minds-live-chat .chat-msg{
	display:block;
	width:100%;
	line-height:125px;
	text-align:center;
	color:#888;
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
.minds-live-chat ul li textarea{
	border:1px solid #CCC;
	border-radius:0;
	padding:4px 8px;
	-webkit-border-radius:0;
	-moz-border-radius:0;
	background:#EEE;
	height:42px;
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
.minds-live-chat ul li.userlist ul {
overflow: hidden;
overflow-y:scroll;
position: relative;
height: 170px;
margin: 0;
}
.minds-live-chat ul li.userlist ul li{
	width:100%;
	height:auto;
	float:none;
	margin:0;
	position:relative;
}
.minds-live-chat ul li.userlist ul li h3{
	font-size:12px;
}

.userlist .prompt{
	font-size:12px;
	line-height:13px;
	color:#333;
	padding:8px;
}
.userlist input[type=password]{
	margin:0;
	padding:16px;
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

/**
 * conversations
 */

.conversations-list{
	border-right:1px solid #EEE;
}
.conversations-list > li{
	display:block;
	width:auto;
	padding:8px;
	height:50px;
	border-bottom:1px solid #EEE;
}
.conversations-list > li:hover{
	background:#EEE;
}
.conversations-list > li.active{
	background:#4690D6;
	color:#FFF;
}
.conversations-list > li .icon{
	float:left;
}
.conversations-list > li > a{
	display: block;
	width: 100%;
	height: 50px;
}
.conversations-list > li h3{
	float:left;
	margin:2px 16px;
}
.conversations-list > li span.ts{
	float:right;
	font-size:11px;
	color:#DDD;
	margin:12px 0;
}
.conversations-list > li div.unread{
	width: 12px;
	height: 12px;
	display: block;
	border-radius: 100%;
	float: right;
	/* position: absolute; */
	background: green;
	color: transparent;
	margin: 14px;
}

.conversation-engage{
	margin:16px;
}
.conversation-engage input[type=text]{
	padding:16px;
}
.conversation-engage input[type=submit]{
	margin-top:8px;
}

.sidebar .conversation-engage{
margin: 16px 0;
}
.sidebar .conversation-engage input[type=text]{
	width:190px;
}

.conversation-wrapper{
	overflow:hidden;
	overflow-y:scroll;
	height:60vh;
	margin:16px;
	padding-right:32px;
}
.message .message-content{
	padding:8px;
	overflow:hidden;
}
.message .time{
	float: right;
	padding-top: 20px;
	font-size: 11px;
	color: #888;
}

.message .actions{
	float:right;
	display:none;
	margin-top:27px;
}
.message:hover .actions{
	display:block;
}
.message:hover .actions a{
	cursor:pointer;
	color:#888;
}

.conversation-configuration{
	padding:16px;
}

.conversation-configuration .large-icon{
	margin:12px 0;
	line-height:200px;
	font-size:200px;
	width:100%;
	text-align:center;
}
.conversation-configuration h3{
	text-align:center;
}
.conversation-configuration p{
text-align:center;
}

.conversation-configuration form{
	width:600px;
	margin:32px auto;
	display:block;
}
.conversation-configuration label{
	float: left;
    padding: 16px;
    width:100px;
}
.conversation-configuration input[type=password]{
	margin:8px 0;
	width: 400px;
	display:block;
	padding: 16px;
}
.conversation-configuration input[type=submit]{
	/*margin-left:16px;*/
	margin:auto;
	display:block;
}

.conversation-unlock input[type=password]{
	padding:16px;
	margin:8px 0;
	display:block;
}

.count{
	position: absolute;
background: red;
border-radius: 100%;
padding: 0 6px;
color: #FFF;
font-size: 10px;
bottom: 23px;
right: -7px;
}
