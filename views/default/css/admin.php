<?php
/**
 * Elgg Admin CSS
 *
 * This is a distinct theme from the theme of the site. There are dependencies
 * on the HTML created by the views in Elgg core.
 *
 * @package Elgg.Core
 * @subpackage UI
 */

?>

/* ***************************************
	SIDEBAR MENU
*************************************** */
.elgg-menu-page > li > a{
	background:#EEE;
	color:#888;
	font-weight:bold;
}
.elgg-admin-sidebar-menu a {
	border: 1px solid red;
	display: block;
	padding: 5px;
	color: #333;
	cursor: pointer;
	text-decoration: none;
	margin-bottom: 2px;
	border: 1px solid #CCC;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.elgg-admin-sidebar-menu a:hover {
	text-decoration: none;
	background: black;
	color: white;
	border: 1px solid black;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > a {
	background-color: #BBB;
}
.elgg-admin-sidebar-menu .elgg-menu-closed:before {
	content: "\25B8";
	padding-right: 4px;
}
.elgg-admin-sidebar-menu .elgg-menu-opened:before {
	content: "\25BE";
	padding-right: 4px;
}
.elgg-admin-sidebar-menu .elgg-child-menu {
	display: none;
	padding-left: 30px;
}
.elgg-admin-sidebar-menu li.elgg-state-selected > ul {
	display: block;
}
.elgg-admin-sidebar-menu h2 {
	padding-bottom: 5px;
}
.elgg-admin-sidebar-menu ul.elgg-menu-page {
	padding-bottom: 15px;
}

