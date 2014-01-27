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

/**
 * Plugin list
 */
#elgg-plugin-list .elgg-plugin{
	border: 1px solid #DDD;
	padding: 16px;
	margin:2px 0;
}

/**
 * Themeset selector
 */
.elgg-input-radios.themesets {
	width:100%;
	height:auto;
	display:block;
	float:left;
}
.elgg-input-radios.themesets > li{
	float:left;
	margin:4px 16px;
	width:45%;
}
@media all and (max-width: 1200px){
	.elgg-input-radios.themesets > li{
		width:100%;
		margin:4px 0;
	}
}
.elgg-input-radios.themesets > li input{
	display:none;
}
.elgg-input-radios.themesets > li img{
	border:2px solid #EEE;
	border-radius:2px;
	-webkit-border-radius:2px;
	-moz-border-radius:2px;
	width:100%;
}
.elgg-input-radios.themesets > li input[type="radio"]:checked + img{
	border:2px solid #4690D6;
}
.elgg-form-themesets-edit .elgg-button{
	margin-right:40px;
	float:right;
}
