<?php
/**
 * Elgg Peek a boo theme
 * @package Peek a boo theme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Web Intelligence
 * @copyright Web Intelligence
 * @link www.webintelligence.ie
 * @version 1.8
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
	color: #4690d6;
	border: 1px solid #4690d6;
	font-size: 12px;
}
.elgg-pagination a:hover {
	background: #4690d6;
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
	margin-bottom: 5px;
	border-bottom: 2px solid #cccccc;
	display: table;
	width: 100%;
}
.elgg-tabs li {
	float: left;
	border: 2px solid #ccc;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 10px;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}
.elgg-tabs a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0 10px;
	text-align: center;
	height: 21px;
	color: #999;
}
.elgg-tabs a:hover {
	background: #dedede;
	color: #4690D6;
}
.elgg-tabs .elgg-state-selected {
	border-color: #ccc;
	//background: white;
}
.elgg-tabs .elgg-state-selected a {
	position: relative;
	top: 2px;
	//background: white;
}

/* ***************************************
	BREADCRUMBS
*************************************** */
.elgg-breadcrumbs {
	font-size: 80%;
	font-weight: bold;
	line-height: 1.2em;
	color: #bababa;
}
.elgg-breadcrumbs > li {
	display: inline-block;
}
.elgg-breadcrumbs > li:after{
	content: "\003E";
	padding: 0 4px;
	font-weight: normal;
}
.elgg-breadcrumbs > li > a {
	display: inline-block;
	color: #999;
}
.elgg-breadcrumbs > li > a:hover {
	color: #0054a7;
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
	float: left;
}

.elgg-menu-topbar > li {
	float: left;
}

.elgg-menu-topbar > li > a {
	padding: 2px 15px 0;
	color: #eee;
	margin-top: 1px;
}

.elgg-menu-topbar > li > a:hover {
	color: #4690D6;
	text-decoration: none;
}

.elgg-menu-topbar-alt {
	float: right;
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
	TOPBAR SETTINGS MENU
*************************************** */
.topmenu-main-admin{
        text-aling: right;
}
.topmenu-main-admin a{
        text-aling: right;
        color: #83CAFF;
        text-decoration: none;
        list-style-type: none;
}
.topmenu-main-admin a:hover{
        text-aling: right;
}

.top-menu-site {
	z-index: 1;
}

.top-menu-site > li > a {
	font-weight: normal;
	padding: 3px 13px 0px 13px;
	height: 20px;
        font-size: 0.9em;
}

.top-menu-site > li > a:hover {
	text-decoration: none;
}


.top-menu-site-default {
	position: absolute;
	bottom: 0;
	right: 0;
	height: 23px;
}

.top-menu-site-default > li {
	float: right;
        border: 0;
        text-align: right;
}

.top-menu-site-default > li > a {
        color: #83CAFF;
        font-weight: bold;
        
}


.top-menu-site-default > .top-state-selected > a,
.top-menu-site-default > li:hover > a {
	color: #83CAFF;
        font-weight: bold;
        //border-bottom: solid 4px #83CAFF;
}

.top-menu-site-more {
	display: none;
	position: relative;
	width: 150px;
        margin: 0;
        margin-top: 0px;
        border-right: solid 4px #83CAFF;
        border-left: solid 4px #83CAFF;
        -webkit-box-shadow: 0 4px 2px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 4px 2px rgba(0, 0, 0, 0.45);
	box-shadow: 0 4px 2px rgba(0, 0, 0, 0.45);
        //opacity: 0;
        //visibility: hidden;
        //-webkit-transition: visibility 0s linear 0.5s,opacity 0.5s linear;
}
.top-menu-site-more-last {
        border:0;
        margin: 0;
        border-bottom: solid 4px #83CAFF;
        border-right: solid 4px #83CAFF;
        border-left: solid 4px #83CAFF;
        border-radius: 0 0 10px 10px;
}

.top-menu-site-more-last > li:last-child > a,
.top-menu-site-more-last > li:last-child > a:hover {
        border-top: 3px solid #555;
        border-radius: 0 0 6px 6px;
}

.top-menu-site-more-admin > li:first-child > a,
.top-menu-site-more-admin > li:first-child > a:hover {
        border-top: 3px solid #555;
        
}


li:hover > .top-menu-site-more {
	display: block;
}

.top-menu-site-more > li > a {
	background: #4A4A4A;
	color: #AAA;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	
}
.top-menu-site-more > li > a:hover {
	background: #83CAFF;
	color: white;
}
.top-menu-site-more > li:last-child > a,
.top-menu-site-more > li:last-child > a:hover {
}

.top-more > a:before {
	content: "\25BC";
	font-size: smaller;
	margin-right: 4px;

}

.top-more{
        list-style-type: none;
        
}






/* ***************************************
	SITE SUB MENU
*************************************** */
.elgg-menu-subsite {
	z-index: 1;
}

.elgg-menu-subsite > li > a {
	font-weight: bold;
	padding: 4px 13px 0px 13px;
	height: 20px;
}

.elgg-menu-subsite > li > a:hover {
	text-decoration: none;
}


.elgg-menu-subsite-default {
	position: absolute;
	bottom: 0;
	left: 440px;
	height: 33px;
}

.elgg-menu-subsite-default > li {
	float: left;
	//margin-right: 1px;
        border: 0;
}

.elgg-menu-subsite-default > li > a {
        color: #AAA;
        background: transparent;
        
        -webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
        
        -webkit-transition: background-color 0.3s, color 0.3s;
        -moz-transition: background-color 0.3s, color 0.3s;
        -o-transition: background-color 0.3s, color 0.3s;
        -ms-transition: background-color 0.3s, color 0.3s;
}

.elgg-menu-subsite-default > .elgg-state-selected > a{
        background: #83CAFF;
        color: white; //#DDD;

	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
        
}
.elgg-menu-subsite-default > .elgg-state-selected:hover > a{
        background: #83CAFF;
        color: #DDD;
}

.elgg-menu-subsite-default > li:hover > a {
	background: #DDD;
	color: #777;
	
	//-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	//-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	//box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	
	-webkit-border-radius: 0 0 4px 4px;
	-moz-border-radius: 0 0 4px 4px;
	border-radius: 0 0 4px 4px;
}

.elgg-menu-subsite-more {
	display: none;
	position: relative;
	left: -1px;
	width: 100%;
	z-index: 1;
	min-width: 150px;
	//border: 1px solid transparent;
        margin: 0 1px;
	border-top: 0;
        border-bottom: solid 4px #CEFF16;
	
	//-webkit-border-radius: 0 0 4px 4px;
	//-moz-border-radius: 0 0 4px 4px;
	//border-radius: 0 0 4px 4px;
	
	//-webkit-box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
	//-moz-box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
	//box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
}

li:hover > .elgg-menu-subsite-more {
	display: block;
}

.elgg-menu-subsite-more > li > a {
	background: #EEE;
	color: #555;
	
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
	
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.elgg-menu-subsite-more > li > a:hover {
	background: #83CAFF;
	color: white;
}
.elgg-menu-subsite-more > li:last-child > a,
.elgg-menu-subsite-more > li:last-child > a:hover {
	//-webkit-border-radius: 0 0 4px 4px;
	//-moz-border-radius: 0 0 4px 4px;
	//border-radius: 0 0 4px 4px;
}

.elgg-more > a:before {
	content: "\25BC";
	font-size: smaller;
	margin-right: 4px;
}



/* ***************************************
	SITE MENU
*************************************** */
.elgg-menu-site {
	z-index: 1;
}

.elgg-menu-site > li > a {
	font-weight: bold;
	padding: 8px 13px 0px 13px;
	height: 25px;
}

.elgg-menu-site > li > a:hover {
	text-decoration: none;
}


.elgg-menu-site-default {
	position: absolute;
	bottom: 0;
	left: 315px;
	height: 33px;
}

.elgg-menu-site-default > li {
	float: left;
	//margin-right: 1px;
        border: 0;
}

.elgg-menu-site-default > li > a {
        color: #AAA;
        
        -webkit-border-radius: 4px 4px 0 0;
	-moz-border-radius: 4px 4px 0 0;
	border-radius: 4px 4px 0 0;
        
        -webkit-transition: background-color 0.3s, color 0.3s;
        -moz-transition: background-color 0.3s ease, color 0.3s;
        -o-transition: background-color 0.3s, color 0.3s;
        -ms-transition: background-color 0.3s, color 0.3s;
}


.elgg-menu-site-default > .elgg-state-selected > a{
        background: #83CAFF;
        color: white; //#DDD;
        border-bottom: solid 4px #83CAFF; //#CEFF16;
	-webkit-border-radius: 4px 4px 0 0;
	-moz-border-radius: 4px 4px 0 0;
	border-radius: 4px 4px 0 0;
}
.elgg-menu-site-default > .elgg-state-selected:hover > a{
        background: #83CAFF;
        color: #DDD;

}

.elgg-menu-site-default > li:hover > a {
	background: #555; //#83CAFF;
        color: #CEFF16;
        text-decoration: none;
        border-bottom: solid 4px #83CAFF; //#CEFF16;
	
	//-webkit-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	//-moz-box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	//box-shadow: 2px -1px 1px rgba(0, 0, 0, 0.25);
	
	-webkit-border-radius: 4px 4px 0 0;
	-moz-border-radius: 4px 4px 0 0;
	border-radius: 4px 4px 0 0;
}

.elgg-menu-site-more {
	display: none;
	position: relative;
	left: -1px;
	width: 100%;
	z-index: 1;
	min-width: 150px;
	//border: 1px solid transparent;
        margin: 0 1px;
	border-top: 0;
        border-bottom: solid 4px #CEFF16;
	
	//-webkit-border-radius: 0 0 4px 4px;
	//-moz-border-radius: 0 0 4px 4px;
	//border-radius: 0 0 4px 4px;
	
	//-webkit-box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
	//-moz-box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
	//box-shadow: 3px 3px 3px 3px rgba(0, 0, 0, 0.25);
}

li:hover > .elgg-menu-site-more {
	display: block;
}

.elgg-menu-site-more > li > a {
	background: #EEE;
	color: #555;
	
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
	
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.elgg-menu-site-more > li > a:hover {
	background: #83CAFF;
	color: white;
}
.elgg-menu-site-more > li:last-child > a,
.elgg-menu-site-more > li:last-child > a:hover {
	//-webkit-border-radius: 0 0 4px 4px;
	//-moz-border-radius: 0 0 4px 4px;
	//border-radius: 0 0 4px 4px;
}

.elgg-more > a:before {
	content: "\25BC";
	font-size: smaller;
	margin-right: 4px;
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
	margin-bottom: 5px;
	border-bottom: 2px solid #ccc;
	display: table;
	//width: 100%;
}
.elgg-menu-filter > li {
	float: left;
	border: 2px solid #ccc;
	border-bottom: 0;
	background: #eee;
	margin: 0 0 0 10px;
	overflow: hidden;
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}
.elgg-menu-filter > li:hover {
	background: #dedede;
    overflow: visible;
}
.elgg-menu-filter > li > a {
	text-decoration: none;
	display: block;
	padding: 3px 10px 0;
	text-align: center;
	height: 21px;
	color: #999;
	float:left;
}
.elgg-menu-filter > li > a:hover {
	//background: #FFF;
	color: #4690D6;
}
.elgg-menu-filter > .elgg-state-selected {
	border-color: #ccc;
	background: white;
    top: 2px;
}
.elgg-menu-filter > .elgg-state-selected:hover {
	border-color: #ccc;
	background: #FFF;
}
.elgg-menu-filter > .elgg-state-selected > a {
	position: relative;
	top: 2px;

	background: white;
}

/* ***************************************
	PAGE MENU
*************************************** */
.elgg-menu-page {
	margin-bottom: 15px;
}

.elgg-menu-page a {
	display: block;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background-color: white;
	margin: 0 0 3px;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-page a:hover {
	background-color: #0054A7;
	color: white;
	text-decoration: none;
}
.elgg-menu-page li.elgg-state-selected > a {
	background-color: #4690D6;
	color: white;
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

	width: 165px;
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
	FOOTER
*************************************** */
.elgg-menu-footer > li,
.elgg-menu-footer > li > a {
	display: inline-block;
	color:#999;
}

.elgg-menu-footer > li:after {
	content: "\007C";
	padding: 0 4px;
}

.elgg-menu-footer-default {
	float: right;
}

.elgg-menu-footer-alt {
	float: left;
}

/* ***************************************
	ENTITY AND ANNOTATION
*************************************** */
<?php // height depends on line height/font size ?>
.elgg-menu-entity, elgg-menu-annotation {
	float: right;
	margin-left: 15px;
	font-size: 90%;
	color: #aaa;
	line-height: 16px;
	height: 16px;
}
.elgg-menu-entity > li, .elgg-menu-annotation > li {
	margin-left: 15px;
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

/* ***************************************
	OWNER BLOCK
*************************************** */
.elgg-menu-owner-block li a {
	display: block;
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background-color: white;
	margin: 3px 0 5px 0;
	padding: 2px 4px 2px 8px;
}
.elgg-menu-owner-block li a:hover {
	background-color: #83CAFF;
	color: white;
	text-decoration: none;
}
.elgg-menu-owner-block li.elgg-state-selected > a {
	background-color: orange;
	color: white;
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
	POPUP HELP
*************************************** */

		.popover {
			position: absolute;
		}
		
		/* Aesthetic styles */
		.popover {
			background: #333;
			color: #fff;
			-moz-border-radius: 5px; /* FF1+ */
			-webkit-border-radius: 5px; /* Saf3+, Chrome */
			-khtml-border-radius: 5px; /* Konqueror */
			border-radius: 5px; /* Standard. IE9 */
			padding: 10px;
			width: 200px;
		}	
	
		#popover2 .after {
		border-bottom: 10px solid transparent;
		border-left: 15px solid #333;
		content: '';
		bottom: -10px;
		height: 0;
		margin-left: -5px;
		left: 50%;
		position: absolute;
		width: 0;
	}
        
        
.footer-container a:hover {
    color: #CEFF16;
    text-decoration: none;
}