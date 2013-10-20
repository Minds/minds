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
.elgg-page-default {
	min-width: 998px;
	height:auto;
	min-height:100%;
}
.elgg-page-default .elgg-page-header > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: 90%;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	padding: 5px 0;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #F8F8F8;
	opacity:0.95;
	position: fixed;
    top:0;
    min-width:998px;
    width:100%;
	height: 100px;
	z-index: 9000;
	/*box-shadow: 0 0 5px #888;
	-moz-box-shadow: 0 0 5px #888;
	-webkit-box-shadow: 0 0 5px #888;*/
}
.elgg-page-topbar > .elgg-inner {
	padding: 15px 10px;
    margin:auto;
    width:90%;
}
.elgg-page-topbar > .elgg-inner > .logo{
	padding: 0 8px;
	float:left;
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
.elgg-page-body{
	position:relative;
	width:100%;
	height:auto;
	min-height:100%;
	margin-top:100px;
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
	background: #F8F8F8; 
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
	background:#F8F8F8;
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
	margin: 10px;
}
.elgg-main > .elgg-list,.elgg-main > .elgg-content{
	margin:0;
	padding:1px 10px;
	background:#F8F8F8;
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
