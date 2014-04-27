<?php
/**
 * Navigation
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGINATION
*************************************** */
.elgg-pagination {
	margin: 10px 0;
	display: block;
	text-align: center;
}
.elgg-pagination li {
	display: inline;
	margin: 0 6px 0 0;
	text-align: center;
}
.elgg-pagination a, .elgg-pagination span {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	
	padding: 2px 6px;
	color: #333;
	border: 1px solid #999;
	font-size: 12px;
}
.elgg-pagination a:hover {
	background: #333;
	color: white;
	text-decoration: none;
}
.elgg-pagination .elgg-state-disabled span {
	color: #CCCCCC;
	border-color: #CCCCCC;
}
.elgg-pagination .elgg-state-selected span {
	color: #555555;
	border-color: #555555;
}

/* ***************************************
	TABS
*************************************** */
.elgg-tabs {
	width: auto;
	margin:auto;
	text-align:center;
}
.elgg-tabs li {
	font-size:16px;	
	display: inline-block;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 3px 25px 0 0;
	text-align: center;
	color: #333;
}
.elgg-tabs a:hover {
	color:  #4690D6;
}
.elgg-tabs .elgg-state-selected {
}
.elgg-tabs .elgg-state-selected a {
	color: #4690D6;
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	margin:0;
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs > li {
	display: inline-block;
}
.elgg-breadcrumbs > li:after {
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #999;
	text-decoration: underline;
}

.elgg-main .elgg-breadcrumbs {
	position: relative;
	top: -6px;
	left: 0;
}

/* ***************************************
	TOPBAR MENU
*************************************** */
.elgg-menu-topbar {
	margin:0;
	float: left;
}

.elgg-menu-topbar > li {
	float: left;
}

.elgg-menu-topbar > li > a {
	padding-top: 2px;
	color: #333;
	margin: 1px 15px 0;
}

.elgg-menu-topbar > li > a:hover {
	color: #4690D6;
	text-decoration: none;
}

.elgg-menu-topbar-alt {
	gloat: right;
}

.elgg-menu-topbar-alt > li > a {
	padding-top: 2px;
	color: #333;
    font-weight:bold;
	margin: 1px 8px 0;
}


.elgg-menu-topbar .elgg-icon {
	vertical-align: middle;
	margin-top: -1px;
}

.elgg-menu-topbar > li > a.elgg-topbar-logo {
	margin-top: 0;
	padding-left: 5px;
	width: 38px;
	height: 20px;
}

.elgg-menu-topbar > li > a.elgg-topbar-avatar {
	width: 18px;
	height: 18px;
}

/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site {
	z-index: 1;
    margin:0;
    clear:left;
}

.elgg-menu-site > li > a {
	font-weight: bold;
	padding: 3px 8px 0px;
	height: 20px;
}

.elgg-menu-site > li > a:hover {
	text-decoration: none;
}

.elgg-menu-site-default {
}

.elgg-menu-site-default > li {
	float: left;
	margin-right: 1px;
}

.elgg-menu-site-default > li > a {
	color: #333;
}

.elgg-menu-site > li > ul {
	display: none;
	/*background-color: white;*/
}

.elgg-menu-site > li:hover > ul {
	display: block;
}

.elgg-menu-site-default > .elgg-state-selected > a,
.elgg-menu-site-default > li:hover > a {
	/*background: white;
	color: #555;

	-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);

	-webkit-border-radius: 4px 4px 0 0;
	-moz-border-radius: 4px 4px 0 0;
	border-radius: 4px 4px 0 0;*/
    color:#4690D6;
}

.elgg-menu-site-more {
	position: absolute;
    top:12px;
	left: -1px;
	width: 100%;
	min-width: 150px;
	border: 1px solid #000;

	-webkit-border-radius: 4px 4px 4px 4px;
	-moz-border-radius:	4px 4px 4px 4px;
	border-radius:	4px 4px 4px 4px;

	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}

.elgg-menu-site-more > li > a {
	background-color: rgba(0, 0, 0, 0.75);
	color: #CCC;

	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;

	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}

.elgg-menu-site-more > li > a:hover {
	/*background: #EEE;*/
    color:#4690D6;
}

.elgg-menu-site-more > li:last-child > a,
.elgg-menu-site-more > li:last-child > a:hover {
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
}

.elgg-more > a:before {
	/*content: "\25BC";*/
	font-size: 12px;
	margin-right: 4px;
	padding-top:-5px;
}

/* ***************************************
	TITLE
*************************************** */
.elgg-menu-title {
	float: right;
}

.elgg-menu-title > li {
	display: inline-block;
	margin-left: 4px;
}

/* ***************************************
	FILTER MENU
*************************************** */
.elgg-menu-filter {
	display: table;
	width: auto;
	margin:16px;
	float:left;
}
.elgg-menu-filter > li {
	float: left;

	font-size:16px;
}
.elgg-menu-filter > li > a {
	text-decoration: none;
	display: block;
	padding: 3px 25px 0 0;
	color:#333;
}
.elgg-menu-filter > li > a:hover {
	color: #4690D6;
}

.elgg-menu-filter > .elgg-state-selected > a {
	color:#4690D6;
}

.elgg-menu-filter-type{
	border-left:2px solid #999;
	padding-left:25px;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page {
	margin-bottom: 15px;
}

.elgg-menu-page a {
	display: block;
	
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
	
	font-weight:bold;
	background-color: #F3F3F3;
	margin: 0 0 3px;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-page a:hover {
	background-color: #EEE;
	text-decoration: none;
}
.elgg-menu-page li.elgg-state-selected > a {
	background-color: #FAFAFA;
	color:#888;
}
.elgg-menu-page .elgg-child-menu {
	display: none;
	margin-left: 15px;
}
.elgg-menu-page .elgg-menu-closed:before, .elgg-menu-opened:before {
	display: inline-block;
	padding-right: 4px;
}
.elgg-menu-page .elgg-menu-closed:before {
	content: "\002B";
}
.elgg-menu-page .elgg-menu-opened:before {
	content: "\002D";
}

/* ***************************************
	HOVER MENU
*************************************** */
.elgg-menu-hover {
	display: none;
	position: absolute;
	z-index: 10000;

	overflow: hidden;

	min-width: 165px;
	max-width: 250px;
	border: solid 1px;
	border-color: #E5E5E5 #999 #999 #E5E5E5;
	background-color: #FFF;
	
	-webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	-moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
	box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.50);
}
.elgg-menu-hover > li {
	border-bottom: 1px solid #ddd;
}
.elgg-menu-hover > li:last-child {
	border-bottom: none;
}
.elgg-menu-hover .elgg-heading-basic {
	display: block;
}
.elgg-menu-hover a {
	padding: 2px 8px;
	font-size: 92%;
}
.elgg-menu-hover a:hover {
	background: #ccc;
	text-decoration: none;
}
.elgg-menu-hover-admin a {
	color: red;
}
.elgg-menu-hover-admin a:hover {
	color: white;
	background-color: red;
}

/* ***************************************
	SITE FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	display: inline-block;
	color: #999;
}

.elgg-menu-footer > li:after {
	content: "\007C";
	padding: 0 4px;
}

.elgg-menu-footer-default {
	width:auto;
    margin:10px auto;
    text-align:center;
    float:right;
}

.elgg-menu-footer-alt {
	float: left;
}

/* ***************************************
	GENERAL MENU
*************************************** */
.elgg-menu-general > li,
.elgg-menu-general > li > a {
	display: inline-block;
	color: #999;
}


/* ***************************************
	ENTITY AND ANNOTATION
*************************************** */
<?php // height depends on line height/font size ?>
.elgg-menu-entity, elgg-menu-annotation {
	float: right;
	margin: 12px;
	font-size: 90%;
	color: #aaa;
	line-height: 16px;
}
.elgg-menu-entity > li, .elgg-menu-annotation > li {
	margin-left:8px;
}
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	color: #aaa;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-entity > li > a, .elgg-menu-annotation > li > a {
	display: block;
}
.elgg-menu-entity > li > span, .elgg-menu-annotation > li > span {
	vertical-align: baseline;
}

.elgg-list .elgg-menu-entity{
	top:0;
	right:0;
	position:absolute;
	z-index:12;
	background:rgba(248, 248, 248, 0.9);
	width:auto;
	margin:0;
	padding:22px 15px;
	display:none;
}
.elgg-item:hover > .elgg-menu-entity{
	display:block;
}
.elgg-list .elgg-menu-entity li{
}
.elgg-menu-entity li.entypo {
	font-size: 12px;
}
/* ***************************************
	OWNER BLOCK
*************************************** */
.elgg-menu-owner-block li a {
	display: block;
	
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	border-radius: 2px;
	
	background-color: transparent;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-owner-block li a:hover {
	background-color: #EEE;
	text-decoration: none;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	background-color: #EEE;
}

/* ***************************************
	LONGTEXT
*************************************** */
.elgg-menu-longtext {
	float: right;
}

/* ***************************************
	RIVER
*************************************** */
.elgg-menu-river {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-river > li {
	display: inline-block;
	margin-left: 5px;
}
.elgg-menu-river > li > a {
	color: #aaa;
	height: 16px;
}
<?php // need to override .elgg-menu-hz ?>
.elgg-menu-river > li > a {
	display: block;
}
.elgg-menu-river > li > span {
	vertical-align: baseline;
}

/* ***************************************
	SIDEBAR EXTRAS (rss, bookmark, etc)
*************************************** */
.elgg-menu-extras {
	margin-bottom: 15px;
}

/* ***************************************
	WIDGET MENU
*************************************** */
.elgg-menu-widget > li {
	position: absolute;
	top: 4px;
	display: inline-block;
	width: 18px;
	height: 18px;
	padding: 2px 2px 0 0;
}

.elgg-menu-widget > .elgg-menu-item-collapse {
	left: 5px;
}
.elgg-menu-widget > .elgg-menu-item-delete {
	right: 5px;
}
.elgg-menu-widget > .elgg-menu-item-settings {
	right: 25px;
}

/**
 * Hover over items
 */
.elgg-menu-item-hover-over > ul{
	display: none;
	position: absolute;
	border: 1px solid #eee;
	width: 100%;
	padding: 4px;
	background: #DDD;
	border-radius: 4px;
	z-index:99;
}
.elgg-menu-item-hover-over:hover > ul{
	display:block;
}
.elgg-menu-item-hover-over > ul > li {
	border-bottom:1px solid #EEE;
	padding:6px 8px;
}
.elgg-menu-item-hover-over > ul > li:last {
	border-bottom:0;
}
.elgg-menu-item-hover-over > ul > li > a{
	font-size:12px;
	color:#666;
	text-decoration:none;
}
.elgg-menu-item-hover-over > ul > li:hover > a, .elgg-menu-item-hover-over > ul > li.elgg-state-selected > a{
	color:#4690D6;
}

/**
 * News filter
 */
.minds-news-filter-box .elgg-menu-filter{
	margin:4px 4px 16px;
}
.minds-news-filter-box .elgg-menu-filter > li{
	font-size:12px;
}
