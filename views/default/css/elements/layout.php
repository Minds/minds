<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 990px wide, centered. Used in default page shell
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
<?php // the width is on the page rather than topbar to handle small viewports ?>
.hero, .elgg-page-default {
	min-width: 998px;
	height:auto;
	min-height:100%;
}
.hero.sidebar-active{
	margin-left:250px;
}
.hero .header > .inner, .elgg-page-default .elgg-page-header > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	height: 90px;
}
.hero > .body > .inner, .elgg-page-default .elgg-page-body > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	padding: 16px 0;
}
.hero > .body > .inner.fixed{
	max-width: 1280px;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	padding: 5px 0;
}

/***** TOPBAR ******/
.hero > .topbar {
	background: #F8F8F8;
	 background: rgba(255,255,255, 0.9);
	position: fixed;
    top:0;
    min-width:998px;
    width:100%;
	height: auto;
	z-index: 8000;
	box-shadow: 0 0 5px #DDD;
	-moz-box-shadow: 0 0 5px #DDD;
	-webkit-box-shadow: 0 0 5px #DDD;
}
.hero > .topbar > .inner{
	padding: 8px;
    margin:auto;
    width:90%;
}
.hero > .topbar > .inner > div{
	float:left;
	position:relative;
}
.hero > .topbar > .inner > .left{
	width:25%;
}
.hero > .topbar > .inner > .center{
	width:50%;
}
.hero > .topbar > .inner > .right{
	width:25%;
    text-align: right;
}
.hero > .topbar > .inner .menu-toggle{
	float:left;
	margin-top:20px;
	font-size:32px;
	font-weight:200;
	cursor:pointer;
}
.hero > .topbar .logo{
	margin:auto;
	padding: 0 8px 12px;
	display:block;
	position:relative;
	width:auto;
}
.hero > .topbar .logo > img{
	height:50px;
	width:auto;
}
.hero > .topbar .search{
	margin: 12px 16px;
	float:left;
	width:200px;
}
.hero > .topbar .search input{
	margin:0;
}
.hero > .topbar .owner_block{
	margin-top:8px;
	float:right;
}
.hero > .topbar .owner_block h3{
	font-size:16px;
}
.hero > .topbar .owner_block > a > img{
	padding:0px 8px 8px;
}
.hero > .topbar .owner_block > a > .text{
	padding:8px;
	float:left;
	text-align:right;
	text-decoration:none;
}
.hero > .topbar .actions{
	margin:21px 6px;
	float:right;
}
.hero > .topbar .more{
	clear:both;
	display:none;
	float:right;
}
.hero > .topbar .right:hover .more{
	display:block;
}
.hero > .topbar .more a{
	color:#333;
	font-size:11px;
}

.hero > .topbar .right .elgg-button{
	margin:12px 8px;
}

.hero > .topbar .right .carrot{
	
}

.hero > .topbar .right .carrot .contents{
	display:none;
}
.hero > .topbar .right .carrot:hover .contents{
	display:block;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 48px;
	right: 20px;
	max-width: 500px;
	z-index: 20000;
}
.elgg-system-messages li {
	margin-top: 10px;
}
.elgg-system-messages li p {
	margin: 0;
}

/***** PAGE HEADER ******/
.elgg-page-header {
	position: relative;
	background: #4690D6 url(<?php echo elgg_get_site_url(); ?>_graphics/header_shadow.png) repeat-x bottom left;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}

/***** PAGE BODY ******/
.hero > .body, .elgg-page-body{
	position:relative;
	width:100%;
	height:auto;
	min-height:100%;
	margin-top:75px;
}

/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	/*min-width:998px;*/
	min-height: 360px;
}
.elgg-layout > .elgg-inner{
	width:90%;
	margin:auto;
}

.elgg-layout-one-sidebar {
	/*background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/sidebar_background.gif) repeat-y right top;*/
}
.elgg-layout-two-sidebar {
	/*background: transparent url(<?php echo elgg_get_site_url(); ?>_graphics/two_sidebar_background.gif) repeat-y right top;*/
}
.elgg-layout-error {
	margin-top: 20px;
}
.elgg-sidebar {
	position:relative;
	float:right;
	padding:0;
	width: 336px;
	height:100%;
	margin: 0 0 0 10px;
	padding:0 0 15px 0;
	background: #FFF; 
	/*-moz-box-shadow: 0 0 3px #888;
	-webkit-box-shadow: 0 0 3px#888;
	box-shadow: 0 0 3px #888;*/
}
.elgg-sidebar-alt {
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 160px;
	margin: 0 10px 0 0;
}
.elgg-main {
	position: relative;
	min-height: 360px;
	padding: 10px;
	background:#FFF;
}
.elgg-layout-one-sidebar .elgg-main {
	position: relative;
	min-height: 360px;
	padding:0;
	/*border-left:2px solid #cccccc;*/
	/*-moz-box-shadow: 0 0 3px #888;
	-webkit-box-shadow: 0 0 3px#888;
	box-shadow: 0 0 3px #888;*/
	margin:0 0 10px 0;
}
.elgg-main > .elgg-breadcrumbs{
	margin:10px;
}
.elgg-main > .elgg-head {
	padding-bottom: 3px;
	border-bottom: 1px solid #CCCCCC;
	margin: 20px;
}
.elgg-main > .elgg-list,.elgg-main > .elgg-content{
	margin:0;
	padding:1px 30px;
	background:#FFF;
}

.hero > .body > .inner.fixed-inner{
	width:100%;
}
.hero > .body > .inner.fixed-inner .main, .hero > .body > .inner.fixed-inner .main > .elgg-list{
	background:transparent;
}
.minds-fixed-layout{
	width:1200px;
	margin: 8px auto;
}
.minds-fixed-header-block{
	width: 100%;
	height: auto;
	padding:0;
	margin-bottom:42px;
	clear:both;
}
.minds-fixed-header-block > h1{
	font-size:64px;
	line-height:64px;
	text-align:center;
	text-shadow:0 0 3px #FFF;
}
.minds-fixed-sidebar-left{
	float:left;
	width:200px;
	height:100%;
	padding:8px;
	box-shadow:0 0 3px #888;
	background:#FFF;
}
.minds-fixed-sidebar-left .minds-fixed-avatar{
	width:100%;
}
.minds-fixed-sidebar-left .channel-social-icons{
	padding:0;
	margin:16px 0;
}
.minds-fixed-sidebar-left .channel-social-icons > a.entypo{
	font-size:33px;
}
.minds-fixed-content{
	float:left;	
	width:960px;
	margin-left:16px;
	clear:right;
}
.minds-fixed-content .main{
	padding:0;
}
.minds-fixed-content .elgg-list.x2 {
	padding:0;
}
.minds-fixed-content .elgg-list.x2 > li{
	margin:0 0 8px 16px;
	width:436px;
}
.minds-fixed-content .elgg-list .minds-fixed-post-box > form{
	width:auto;
	padding:0;
	padding-bottom:8px;
}

/**
 * Sidebar
 */
.global-sidebar{
	z-index:99999;
	top:0;
	left:0;
	display:none;
	position:fixed;
	height:100%;
	width:225px;
	padding:12px;
	background:#222;
	box-shadow:0 0 4px #000;
}
.global-sidebar.show{
	display:block;
}

.elgg-footer{
	margin-right:350px;
}
.elgg-footer > .elgg-list{
	clear:none;
	padding:0;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	min-width:998px;
	color: #999;
	font-size:11px;
	background:#FFF;
	position: relative;
	height:80px;
	width:100%;
}
.elgg-page-footer > .elgg-inner{
	border-top:1px solid #DDD;
}
.elgg-page-footer a:hover {
	color: #666;
}
.elgg-page-footer .logo{
	margin:10px auto;
	float:left;
}
