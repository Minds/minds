<?php
/**
 * Specific css for the elgg mobile plugin
 * written by Mark Harding @ kramnorth
 * copyright Kramnorth 2011
 */
?>

.logo_centre{
margin:auto;
width:100%;
height:50px;
}
img.logo_centre{
height:22px;
width:42px;
 display: block;
  margin-left: auto;
  margin-right: auto;
}
.elgg-menu-extras-default{
display:none;
}
.elgg-header{
	background-color:#999;
    }
.elgg-header .ui-btn-corner-all {
	-moz-border-radius: 				0 /*{global-radii-buttons}*/;
	-webkit-border-radius: 			0 /*{global-radii-buttons}*/;
	border-radius: 					0 /*{global-radii-buttons}*/;
    }
    
/*INPUT FIELD AND BUTTON MODS */
fieldset > div {
	margin-bottom: 15px;
    padding-left:10px;
}

/* Widgets settings 
 */
.elgg-menu.elgg-menu-widget.elgg-menu-hz.elgg-menu-widget-default{
	display:none;

/* Widgets layouts
 */
.elgg-col-1of3, .elgg-col-2of3, .elgg-col-3of3{
	width:100%;
 
}

/***** PAGE BODY LAYOUT ******/
.elgg-layout {
	min-height: 360px;
}
.elgg-layout-one-column {
	padding: 0;
}

.elgg-sidebar {
	clear:both;
	position: relative;
	padding: 5px 0px;
    float:left;
	width: 100%;
	margin: 0 0 0 10px;
}
.elgg-sidebar-alt {
	clear:both;
	position: relative;
	padding: 20px 10px;
	float: left;
	width: 100%;
	margin: 0 10px 0 0;
}
.elgg-main {
	width:100%;
	clear:both;
	position: relative;
	min-height: 360px;
	padding: 5px;
}
.elgg-main > .elgg-head {
	padding-bottom: 3px;
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 10px;
}

/**
 * River mods
 */
.news-show-more{
	width:100%;
	text-align:center;
	height:50px;
	background-color:#CCC;
	color:#333;
}
