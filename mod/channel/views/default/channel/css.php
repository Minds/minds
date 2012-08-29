<?php
/**
 * Elgg Profile CSS
 * 
 * @package Profile
 */
?>
/* ***************************************
	Channel Profile
*************************************** */
.elgg-avatar-large > a > img{
	width:425px;
}
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
