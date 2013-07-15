<?php
/**
 * Page Layout
 *
 * Contains CSS for the page shell and page layout
 *
 * Default layout: 981px wide, centered. Used in default page shell
 *
 */
?>
/* <style>
/* ***************************************
	PAGE LAYOUT
*************************************** */
/***** DEFAULT LAYOUT ******/
.elgg-page-default .elgg-page-header > .elgg-inner {
	width: 981px;
	margin: 0 auto;
	height: 90px;
}
.elgg-page-default .elgg-page-body > .elgg-inner {
	width: 981px;
	margin: 0 auto;
}

.elgg-page-footer {
	width: 981px;
	margin: 0 auto;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	margin-left: 181px;
	padding: 8px;
}

.elgg-page-footer > .elgg-inner:after {
	display:block;
	content: '.';
	clear:both;
	visibility:hidden;
	height:0;
}

/***** TOPBAR ******/
.elgg-page-topbar {
	background: #e9e9e9;
	position: fixed;
	height: 38px;
	left: 0;
	right: 0;
	z-index: 10000;
   -moz-box-shadow: 0px 0px 10px #000000;
   -webkit-box-shadow: 0px 0px 10px #000000;
   box-shadow: 0px 0px 10px #000000;
}

.elgg-page-topbar > .elgg-inner {
	padding-top: 6px;
	width: 981px;
	margin: 0 auto;
	position: relative;
}

.elgg-page-topbar ~ .elgg-page-body {
	padding-top: 38px;
}

/***** PAGE MESSAGES ******/
.elgg-system-messages {
	position: fixed;
	top: 24px;
	right: 20px;
	max-width: 500px;
	z-index: 1000;
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
	background:#e9e9e9;
		z-index: 10000;
   -moz-box-shadow: 0px 0px 10px #000000;
   -webkit-box-shadow: 0px 0px 10px #000000;
   box-shadow: 0px 0px 10px #000000;
}
.elgg-page-header > .elgg-inner {
	position: relative;
}

/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
}

.elgg-layout-one-column {
	padding: 10px 0;
}

.elgg-sidebar {
	position: relative;
	padding: 20px 0;
	float: left;
	width: 181px;
	min-height: 360px;
}
.elgg-sidebar-alt {
	position: relative;
	float: right;
	width: 244px;
	margin-left: 20px;
	min-height: 360px;
}

.elgg-sidebar-wg {
	position: relative;
	float: right;
	width: 244px;
	margin-left: 20px;
	min-height: 360px;
}

.elgg-main {
	position: relative;
	min-height: 360px;
}

.elgg-layout-two-sidebar > .elgg-body,
.elgg-layout-one-sidebar > .elgg-body {
	border: 10px solid #B3B3B3;
	border-bottom:none;
	border-top: 0;
	padding: 15px 20px;
}

.elgg-layout > .elgg-body > .elgg-head {
	padding-bottom: 3px;
	margin-bottom: 10px;
}

/***** PAGE FOOTER ******/
.elgg-page-footer {
	position: relative;
	color: #7b7b7c;
}
.elgg-page-footer a:hover {
	color: #323232;
}