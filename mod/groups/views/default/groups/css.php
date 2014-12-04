<?php
/**
 * Elgg Groups css
 * 
 * @package groups
 */

?>
.minds-group-list{
	padding:0 !important;
}
.minds-group-list > li{
	margin: 8px;
	height: auto;
	width: 45%;
}
@media all 
and (min-width : 0px)
and (max-width : 1200px) {
	.minds-group-list > li{
		width:42%;
	}
}
.group-action-button{
	position:absolute;
	right:20px;
	top:40px;
}
.elgg-head .elgg-button-action{
	padding:8px;
	min-width:100px;
}
.elgg-list .group-avatar{

	margin: 0 -5%;
	width: 110%;
	height: auto;
}

.groups-profile > .elgg-image {
	margin-right: 10px;
}
.sidebar .groups img{
	width:100%;
	height:auto;
	clear:both;
	display:block;
}

.group-profile .elgg-sidebar li.elgg-item .elgg-avatar img{
	width:auto;
}

.group-profile .elgg-form-wall-add{
	width:96%;
}
.group-profile .elgg-form-wall-add textarea{
	width: 82%;
}
.group-profile .elgg-form-wall-add .elgg-button-submit{
	display:block;
}

.groups-stats {
	background: #eeeeee;
	padding: 5px;
	margin-top: 10px;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}

.groups-profile-fields .odd,
.groups-profile-fields .even {
	background: #f4f4f4;
	
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	padding: 2px 4px;
	margin-bottom: 7px;
}

.groups-profile-fields .elgg-output {
	margin: 0;
}

#groups-tools > li {
	width: 48%;
	min-height: 200px;
	margin-bottom: 40px;
}

#groups-tools > li:nth-child(odd) {
	margin-right: 4%;
}

.groups-widget-viewall {
	float: right;
	font-size: 85%;
}

.groups-latest-reply {
	float: right;
}

.elgg-menu-groups-my-status li a {
	display: block;

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;

	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-groups-my-status li a:hover {
	background-color: #0054A7;
	color: white;
	text-decoration: none;
}
.elgg-menu-groups-my-status li.elgg-state-selected > a {
	background-color: #4690D6;
	color: white;
}


.group-banner .minds-body-header{
	height:320px;
}
