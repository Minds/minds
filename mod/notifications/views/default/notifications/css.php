<?php
/**
 * Elgg notifications CSS
 * 
 * @package notifications
 */
?>

#notification{
	height:auto;
    min-height:240px;
    position:fixed;
	right:5%;
	overflow-x:hidden;
	overflow-y:scroll;
}
#notification li{
	width:auto;
	height:auto;
	float:none;
}

#notificationstable td.namefield {
	width:250px;
	text-align: left;
	vertical-align: middle;
}
#notificationstable td.namefield p {
	margin:0;
	vertical-align: middle;
	line-height: 1.1em;
	padding:5px 0 5px 0;
}
#notificationstable td.namefield p.namefieldlink {
	margin:5px 0 0 0;
}
#notificationstable td.namefield a img {
	float:left;
	width:25px;
	height:25px; 
	margin:5px 10px 5px 5px;
}
#notificationstable td.emailtogglefield,
#notificationstable td.smstogglefield {
	width:50px;
	text-align: center;
	vertical-align: middle;
}
#notificationstable td.spacercolumn {
	width:30px;
}
#notificationstable td {
	border-bottom: 1px solid silver;
}
#notificationstable td.emailtogglefield input {
	margin-right:36px;
	margin-top:5px;
}
#notificationstable td.emailtogglefield a {
	width:46px;
	height:24px;
	cursor: pointer;
	display: block;
	outline: none;
}
#notificationstable td.sitetogglefield {
	width:50px;
	text-align: center;
	vertical-align: middle;
}
#notificationstable td.sitetogglefield input {
	margin-right:36px;
	margin-top:5px;
}
#notificationstable td.sitetogglefield a {
	width:46px;
	height:24px;
	cursor: pointer;
	display: block;
	outline: none;
}
#notificationstable td.emailtogglefield a.emailtoggleOff {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right 2px;
}
#notificationstable td.emailtogglefield a.emailtoggleOn {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_email.gif) no-repeat right -36px;
}
#notificationstable td.sitetogglefield a.sitetoggleOff {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right 2px;
}
#notificationstable td.sitetogglefield a.sitetoggleOn {
	background: url(<?php echo elgg_get_site_url(); ?>mod/notifications/graphics/icon_notifications_site.gif) no-repeat right -37px;
}
.notification_friends,
.notification_personal,
.notifications_per_user {
	margin-bottom: 25px;
}

.notifications.popup{
	width:336px;
}

.notify_time{
	float:right;
    font-size:10px;
}
.notify_description{
	font-size:11px;
    
}

.notification.notifier{
}  
.notification.notifier:hover, .notification.notifier.new{
	color:#4690D6;
}  
  
#notification .notification-unread{
	background:#EEEED1;
}
#notification li div{
	padding:2px;
}
.notification-new {
	color: white;
	background-color: red;
	
	-webkit-border-radius: 10px; 
	-moz-border-radius: 10px;
	border-radius: 10px;
	
	-webkit-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.50);
	
	position: absolute;
	text-align: center;
	top: 10px;
	left: 10px;
	min-width: 14px;
	height: 14px;
	font-size: 8px;
	font-weight: bold;
}
.notifications .elgg-list{
	width:auto;
}
.notifications .elgg-item{
	width:auto;
	height:auto;
	float:none;
	margin:5px 0;
}

.notification_subscriptions h3{
	font-weight:bold;
}
.notification_subscriptions > div{
	margin-bottom:24px;
}
