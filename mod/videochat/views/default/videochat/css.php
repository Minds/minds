<?php
?>

.videochat_new {
	padding:5px 10px 5px 10px;
	margin:0 0 20px 0;
	border:1px solid silver;
	background: white;
	-moz-border-radius: 8px;
	-webkit-border-radius: 8px;
}

.videochat-singlepage {
	margin:0 10px 0 10px;
 	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}

.videochat-singlepage .room_body {
	background: white;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}
.collapsable_box_content .room_body {
	line-height:1.2em;
}
.videochat-singlepage .videochat-room {
	margin-bottom:5px;
}
.videochat-room {
	background:#cccccc;
	margin-bottom:10px;
 	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
 	border-bottom:1px solid #aaaaaa;
	border-right:1px solid #aaaaaa;
}

.videochat-room .room_room {
	font-size:90%;
	color:#666666;
	padding:0;
}
.videochat_icon {
    float:left;
    margin:0 8px 4px 2px;
}

.room_body {
	margin:0;
	padding:6px 4px 4px 4px;
	min-height: 40px;
	line-height: 1.4em;
}
.videochat_options {
	float:right;
	width:65px;
}
.videochat-room .delete_room {
	width:14px;
	height:14px;
	margin:3px 0 0 0;
	float:right;
}
.videochat-room .delete_room a {
	display:block;
	cursor: pointer;
	width:14px;
	height:14px;
	background: url("<?php echo $vars['url']; ?>_graphics/icon_customise_remove.png") no-repeat 0 0;
	text-indent: -9000px;
}
.videochat-room .delete_room a:hover {
	background-position: 0 -16px;
}
/* IE 6 fix */
* html .videochat-room .delete_room a { background-position-y: 2px; }
* html .videochat-room .delete_room a:hover { background-position-y: -14px; }


textarea#videochat_large-textarea {
	width: 664px;
	height: 40px;
	padding: 6px;
	font-family: Arial, 'Trebuchet MS','Lucida Grande', sans-serif;
	font-size: 100%;
	color:#666666;
}
/* IE 6 fix */
* html textarea#videochat_large-textarea {
	width: 642px;
}

input.videochat_characters_remaining_field {
	color:#333333;
	border:none;
	font-size: 100%;
	font-weight: bold;
	padding:0 2px 0 0;
	margin:0;
	text-align: right;
	background: white;
}
input.videochat_characters_remaining_field:focus {
	border:none;
	background:white;
}
.videochat_characters_remaining {
	text-align: right;
}

