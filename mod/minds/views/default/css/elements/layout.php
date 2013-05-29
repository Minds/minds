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
	width: 990px;
	margin: 0 auto;
	height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding-top:50px;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 990px;
	margin: 0 auto;
	padding: 5px 0;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #E3E3E3 url(<?php echo elgg_get_site_url(); ?>mod/minds/graphics/header_bg.png) repeat-x top left;
	border-bottom: 1px solid #CCC;
	position: fixed;
    top:0;
    width:100%;
	height: 40px;
	z-index: 9000;
}
.elgg-page-topbar > .elgg-inner {
	padding: 0px 10px;
    margin:auto;
    width:990px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 24px;
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
	width:100%;
	height:auto;
	min-height:100%;
}

/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
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
	position: relative;
	padding:0;
	float: right;
	width: 210px;
	margin: 0 0 0 10px;
	padding:0 0 15px 0;
	background: rgb(255, 255, 255); /* The Fallback */
	background: rgba(255, 255, 255, 0.75); 
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius:5px;
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
}
.elgg-layout-one-sidebar .elgg-main {
	position: relative;
	min-height: 360px;
	padding:0;
	background: rgb(255, 255, 255); /* The Fallback */
    /*background: rgba(255, 255, 255, 0.75); */
	/*border-left:2px solid #cccccc;*/
	-webkit-border-radius: 5px;
  	-moz-border-radius: 5px;
 	border-radius:5px;
	/*-moz-box-shadow: 0 0 3px #888;
	-webkit-box-shadow: 0 0 3px#888;
	box-shadow: 0 0 3px #888;*/
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
	padding:10px;
	background:#FFF;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
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
