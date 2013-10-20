<?php
/**
 * Elgg Profile CSS
 * 
 * @package Profile
 */
?>

.elgg-layout.channels{
	max-width: 1600px;
	margin: auto;
}
/* ***************************************
	Channel Profile
*************************************** */
.profile {
	float:left;
	margin-bottom: 15px;
}
.profile .elgg-inner {
	margin: 0;
	border: 2px solid #eee;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
#profile-details {
	padding: 15px;
}
/*** ownerblock ***/
#profile-owner-block {
	/*float: left;*/
	background-color: #eee;
	padding: 15px;
}
#profile-owner-block .large {
	margin-bottom: 10px;
}
#profile-owner-block .profile-action-menu{
	min-height:20px;
}
#profile-owner-block .profile-action-menu li{
	float:left;
	margin-right:10px;
}
#profile-owner-block .profile-content-menu{
	display:none;
}
.profile-content-menu a {
	display: block;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-content-menu a:hover {
	background: #0054A7;
	color: white;
	text-decoration: none;
}
.profile-admin-menu {
	display: none;
}
.profile-admin-menu-wrapper a {
	display: block;
	
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	background-color: #EDEDED;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.profile-admin-menu-wrapper {
	background-color: white;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
.profile-admin-menu-wrapper li a {
	background-color: white;
	color: #333;
	margin-bottom: 0;
}
.profile-admin-menu-wrapper a:hover {
	color: black;
}
/*** profile details ***/
#profile-details .odd {
	background-color: #f4f4f4;
	
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	margin: 0 0 7px;
	padding: 2px 4px;
}
#profile-details .even {
	background-color:#f4f4f4;
	
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	margin: 0 0 7px;
	padding: 2px 4px;
}
.profile-aboutme-title {
	background-color:#f4f4f4;
	
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	margin: 0;
	padding: 2px 4px;
}
.profile-aboutme-contents {
	padding: 2px 0 0 3px;
}
.profile-banned-user {
	border: 2px solid red;
	padding: 4px 8px;
	
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
	border-radius: 6px;
}

/**
 * Widget Modifications
 *
 */
.elgg-col-1of2{
	width:48%;
}
#elgg-widget-col-2.elgg-col-1of2{
	float:none;
	min-height:300px;
}
.elgg-module-widget{
	-webkit-border-radius: 6px; 
	-moz-border-radius: 6px;
	border-radius: 6px;
	background:#F2F2F2;
	border-bottom: 2px solid #DEDEDE;
}
.elgg-module-widget > .elgg-head{
	background:transparent;
}
.elgg-module-widget:hover h3, .elgg-module-widget:hover{
	background:#EEE;
}
.elgg-module-widget > .elgg-body{
	border:none;
}

.elgg-widget-add-control{
	display:block;
	width:100%;
	height:25px;
}

/**
 * Channel list layout
 */
.channels .elgg-list {
	padding:0;
	width:100%;
}
.channels .elgg-list li{
	float: left;
	margin: 15px;
	border: 0;
	width: 260px;
	overflow: hidden;
	height: 125px;
	display: block;
	box-shadow:none;
	-webkit-box-shadow:none;
	-moz-box-shadow:none;
}

/**
 * Custom Channels
 *
 */
.elgg-input-text.colorpicker{
	width:150px;
}
.elgg-button.elgg-button-action.channel{
	margin-left:5px;
}

/**
 * Subscribed will show a blue colour
 */
.subscribe.subscribed{
	background:#4690D6 !important;
	color:#FFF;
	font-weight:normal;
}
.subscribed:hover{
	background:#4690D6;
	color:#FFF;
}
.elgg-owner-block .subscribe{
	margin:5px 0;
	background:#333;
	clear:both;
	min-width:0;
	float:right;	
}
.elgg-list .subscribe{
	margin:0 -5px;
	background:#333;
}
.subscribe{
	border:0;
}

/**
 * Channel elements dropdown
 */
.elgg-button-channel-elements{
	margin-left:10px;
	color:#FFF;
	background: #333 url(http://www.minds.com/_graphics/button_graduation.png) repeat-x left 10px;
}

.elgg-button-channel-elements:before {
	content: "\25BC";
	font-size: smaller;
	margin-right: 4px;
}
.elgg-menu-channel-elements.owner_block{
	margin-top:-30px;
	width:50px;
}
.elgg-menu-channel-elements{
	float:right;
}
.elgg-menu-channel-elements > ul {
	display: none;
	background-color: rgba(0, 0, 0, 0.75);
}

.elgg-menu-channel-elements:hover > ul {
	display: block;
}

.elgg-menu-channel-elements-dropdown {
	position: absolute;
	right:4px;
	width: 150px;
	border: 1px solid #000;
	z-index:10;

	-webkit-border-radius: 4px 4px 4px 4px;
	-moz-border-radius:	4px 4px 4px 4px;
	border-radius:	4px 4px 4px 4px;

	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}

.elgg-menu-channel-elements-dropdown > li > a {
	color: #CCC;
	
	font-weight: bold;
	text-decoration:none;
	padding: 3px 13px 0px 13px;
	height: 20px;
	
	float:right;
	clear:both;

	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;

	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}

.elgg-menu-channel-elements-dropdown > li > a:hover {
	/*background: #EEE;*/
    color:#4690D6;
}

.elgg-menu-channel-elements-dropdown > li:last-child > a,
.elgg-menu-channel-elements-dropdown > li:last-child > a:hover {
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
}
.elgg-widget-instance-channel_avatar img{
	width:100%;
}
.channel .minds-body-header{
	background:transparent !important;
	opacity:100;
}
.channel .elgg-widget-content .elgg-list-entity .elgg-item{
	width:42% !important;
}
