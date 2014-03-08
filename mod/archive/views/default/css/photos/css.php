<?php
/**
 * Photo/image lightbox CSS
 */

?>

/* ***************************************
	Photo lightbox
*************************************** */

.fancybox2-lock #fancybox-wrap {
	z-index: 9002;
}

.tidypics-lightbox-wrap {
    height: 100% !important;
    left: 0 !important;
    top: 0 !important;
    width: 100% !important;
}

.tidypics-lightbox-wrap .fancybox2-skin {
	height: 100% !important;
	width: 100% !important;
	padding: 0 !important;
	-webkit-border-radius: 0;
	-moz-border-radius: 0;
	border-radius: 0;
}

.tidypics-lightbox-wrap .fancybox2-outer {
	background: #000000;
	height: 100%;
}

.tidypics-lightbox-wrap .fancybox2-inner {
	width: 100% !important;
	height: 100% !important;
}

/* Lightbox inner content */
.tidypics-lightbox-container {
	display: table;
	height: 100%;
	width: 100%;
}

.tidypics-lightbox-container .tidypics-lightbox-header {
	display: table-row;
	height: 60px;
}

.tidypics-lightbox-container .tidypics-lightbox-header .tidypics-lightbox-keys-legend {
	color: #CCCCCC;
	float: left;
	font-size: 13px;
	padding: 16px 40px 15px 15px;
	width: 350px;
}

.tidypics-lightbox-container .tidypics-lightbox-header .elgg-menu-entity > li > a {
	color: #AAAAAA;
}

.tidypics-lightbox-container .tidypics-lightbox-header .tidypics-lightbox-header-metadata {
	float: right;
    padding: 16px 40px 15px 15px;
}

.tidypics-lightbox-container .tidypics-lightbox-header a.tidypics-lightbox-close {
	float: right;
	margin-left: 15px;
    margin-top: 2px;
    font-weight: bolder;
    color: #FFFFFF;
}

.tidypics-lightbox-container .tidypics-lightbox-header a.tidypics-lightbox-close .fancybox2-close {
	right: 14px;
    top: 12px;
}

.tidypics-lightbox-container .tidypics-lightbox-header a.tidypics-lightbox-close:hover {
	cursor: pointer;
	text-decoration: none;
}

.tidypics-lightbox-container .tidypics-lightbox-middle {
	display: table;
	width: 100%;
	height: 100%;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-middle-container {
	display: table-cell;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar {
	display: table-cell;
	width: 475px;
	vertical-align: top;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar .tidypics-lightbox-photo-title {
	border-bottom: 1px solid #CCCCCC;
	margin-bottom: 5px;
	position: relative;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar .tidypics-lightbox-photo-description {
	margin-left: 10px;
	position: relative;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar .tidypics-lightbox-photo-tags {
	position: relative;
	padding-bottom: 6px;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar .none {
	color: #666666;
}

.tidypics-lightbox-container .tidypics-lightbox-middle .tidypics-lightbox-sidebar .tidypics-lightbox-other {
	margin: 10px;
	color: #666666;
	border-top: 1px solid #DDDDDD;
	padding-top: 9px;
}

.tidypics-lightbox-container .tidypics-lightbox-footer {
	height: 60px;
    position: fixed;
    width: 100%;
    bottom: 0;
}

.tidypics-lightbox-container .tidypics-photo {
	display: none;
	min-height: 50px;
}

.tidypics-lightbox-container .tidypics-photo-wrapper {
	padding-top: 25px;
	padding-bottom: 25px;
}

.tidypics-lightbox-container .tidypics-lightbox-sidebar-content {
	background: none repeat scroll 0 0 #FFFFFF;
	-moz-border-radius: 5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
    border-radius: 5px 5px 5px 5px;
    margin: 10px;
    padding: 5px;
    width: 435px;
    overflow-y: auto;
}

.tidypics-lightbox-container .tidypics-lightbox-can-edit:hover .tidypics-lightbox-edit-overlay {
	display: block;
}

.tidypics-lightbox-container .tidypics-lightbox-edit-overlay {
	display: none;
	position: absolute;
    right: 0;
    top: 0;
}

.tidypics-lightbox-container .tidypics-lightbox-edit-overlay a {
	background: #FFFFFF;
	border: 1px solid #BBBBBB;
	padding: 2px;
	width: 40px;
	text-align: center;
	display: block;
	text-decoration: none;
	-webkit-box-shadow: 1px 1px 5px #CCC;
	-moz-box-shadow: 1px 1px 5px #CCC;
	box-shadow: 1px 1px 5px #CCC;
}

.tidypics-lightbox-container .tidypics-lightbox-edit-title {
	width: 65%;
	margin-bottom: 2px;
}

.tidypics-lightbox-container .tidypics-lightbox-edit-description {
	margin-bottom: 5px;
	height: 150px;
}

.tidypics-lightbox-container .tidypics-lightbox-edit-tags {
	margin-bottom: 7px;
    margin-top: 3px;
}

/* ***************************************
	Move to album lightbox
*************************************** */

#tidypics-move-to-album-lightbox {
	width: 450px;
	overflow: hidden;
}

#tidypics-move-to-album-lightbox .elgg-image-block {
	border-bottom: 1px dotted #DDDDDD;
	margin: 4px 0;
}

#tidypics-move-to-album-lightbox .elgg-image-block:first-child {
	border-top: 1px dotted #DDDDDD;
	padding-top: 5px;
}

#tidypics-move-to-album-lightbox .elgg-photo {
	height: 40px;
	padding: 2px;
	width: 40px;
}

#tidypics-move-to-album-lightbox .elgg-image-block .elgg-body {
	color: #444444;
	font-size: 14px;
	font-weight: bold;
	padding-top: 15px;
}

#tidypics-move-to-album-lightbox .elgg-image-block .elgg-image {
	padding-top: 14px;
}


/* ***************************************
	Keyboard cues
*************************************** */

#fancybox2-buttons .light-keys {
	
}

/* ***************************************
	Keys.css (see vendors)
*************************************** */


/* Base style, essential for every key. */
kbd, .key {
	display: inline;
	display: inline-block;
	min-width: 1em;
	padding: .2em .3em;
	font: normal .85em/1 "Lucida Grande", Lucida, Arial, sans-serif;
	text-align: center;
	text-decoration: none;
	-moz-border-radius: .3em;
	-webkit-border-radius: .3em;
	border-radius: .3em;
	border: none;
	cursor: default;
	-moz-user-select: none;
	-webkit-user-select: none;
	user-select: none;
}
kbd[title], .key[title] {
	cursor: help;
}

/* Dark style for display on light background. This is the default style. */
kbd, kbd.dark, .dark-keys kbd, .key, .key.dark, .dark-keys .key {
	background: rgb(80, 80, 80);
	background: -moz-linear-gradient(top, rgb(60, 60, 60), rgb(80, 80, 80));
	background: -webkit-gradient(linear, left top, left bottom, from(rgb(60, 60, 60)), to(rgb(80, 80, 80)));
	color: rgb(250, 250, 250);
	text-shadow: -1px -1px 0 rgb(70, 70, 70);
	-moz-box-shadow: inset 0 0 1px rgb(150, 150, 150), inset 0 -.05em .4em rgb(80, 80, 80), 0 .1em 0 rgb(30, 30, 30), 0 .1em .1em rgba(0, 0, 0, .3);
	-webkit-box-shadow: inset 0 0 1px rgb(150, 150, 150), inset 0 -.05em .4em rgb(80, 80, 80), 0 .1em 0 rgb(30, 30, 30), 0 .1em .1em rgba(0, 0, 0, .3);
	box-shadow: inset 0 0 1px rgb(150, 150, 150), inset 0 -.05em .4em rgb(80, 80, 80), 0 .1em 0 rgb(30, 30, 30), 0 .1em .1em rgba(0, 0, 0, .3);
}

/* Light style for display on dark background. */
kbd.light, .light-keys kbd, .key.light, .light-keys .key {
	background: rgb(250, 250, 250);
	background: -moz-linear-gradient(top, rgb(210, 210, 210), rgb(255, 255, 255));
	background: -webkit-gradient(linear, left top, left bottom, from(rgb(210, 210, 210)), to(rgb(255, 255, 255)));
	color:  rgb(50, 50, 50);
	text-shadow: 0 0 2px rgb(255, 255, 255);
	-moz-box-shadow: inset 0 0 1px rgb(255, 255, 255), inset 0 0 .4em rgb(200, 200, 200), 0 .1em 0 rgb(130, 130, 130), 0 .11em 0 rgba(0, 0, 0, .4), 0 .1em .11em rgba(0, 0, 0, .9);
	-webkit-box-shadow: inset 0 0 1px rgb(255, 255, 255), inset 0 0 .4em rgb(200, 200, 200), 0 .1em 0 rgb(130, 130, 130), 0 .11em 0 rgba(0, 0, 0, .4), 0 .1em .11em rgba(0, 0, 0, .9);
	box-shadow: inset 0 0 1px rgb(255, 255, 255), inset 0 0 .4em rgb(200, 200, 200), 0 .1em 0 rgb(130, 130, 130), 0 .11em 0 rgba(0, 0, 0, .4), 0 .1em .11em rgba(0, 0, 0, .9);
}