<?php
/**
 * Elgg embed CSS - standard across all themes
 * 
 * @package embed
 */
?>
.embed-wrapper {
	width: auto;
	margin: 5px;
        overflow:hidden;
}
.embed-wrapper > form {
        overflow-x:auto;
        height:220px;
}

.embed-wrapper > .elgg-list {
        overflow-x:auto;
        height:220px;
}
.embed-content {
        background:#fff;
        padding:5px;
}
.embed-wrapper h2 {
	color: #333333;
	margin-bottom: 10px;
}
.embed-wrapper .elgg-item {
	cursor: pointer;
}

/* ***************************************
	EMBED TABBED PAGE NAVIGATION
*************************************** */
.embed-wrapper .elgg-tabs a:hover {
	color: #666;
}

.embed-wrapper p {
	color: #333;
}
.embed-wrapper .elgg-image-block:hover {
	background-color: #eee;
}<?php
return true;
?>
/***************
 Form
******************/
/* input field classes */
.embed-wrapper .elgg-input-text,
.embed-wrapper .elgg-input-tags {
	font: 120% Arial, Helvetica, sans-serif;
	padding: 5px;
	border: 1px solid #ccc;
	color:#666;
	background-color: white;

	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}
.embed-wrapper .elgg-input-text:focus,
.embed-wrapper .elgg-input-tags:focus {
	border: solid 1px #4690d6;
	background: #e4ecf5;
	color:#333;
}
.embed-wrapper .elgg-input-file {
	background-color: white;
}

.embed-wrapper label {
	color:#333;
}




#facebox {
	position: absolute;
	top: 0;
	left: 0;
	z-index: 10000;
	text-align: left;
}
#facebox .popup {
	position: relative;
}
#facebox .body {
	padding: 10px 15px 20px 15px;
	background-color: white;
	width: 730px;
	min-height:400px;
	
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	border-radius: 8px;
}
#facebox .loading {
	text-align: center;
	padding: 100px 10px 100px 10px;
}
#facebox .image {
	text-align: center;
}
#facebox .footer {
	float: right;
	width:22px;
	height:22px;
	margin:-4px 0 0 0;
	padding:0;
}
#facebox .footer img.close_image {
	background: url(<?php echo elgg_get_site_url(); ?>mod/embed/images/close_button.gif) no-repeat left top;
}
#facebox .footer img.close_image:hover {
	background: url(<?php echo elgg_get_site_url(); ?>mod/embed/images/close_button.gif) no-repeat left -31px;
}
#facebox_overlay {
	position: fixed;
	top: 0px;
	left: 0px;
	height:100%;
	width:100%;
}
.facebox_hide {
	z-index:-100;
}
.facebox_overlayBG {
	background-color: #000;
	z-index: 9999;
}
* html #facebox_overlay { /* ie6 hack */
	position: absolute;
	height: expression(document.body.scrollHeight > document.body.offsetHeight ? document.body.scrollHeight : document.body.offsetHeight + 'px');
}

#facebox .body .content h2 {
	color:#333333;
}


/* embeded content links */
.embeded_file.link {
	display: inline;
	margin-right: 5px;
	padding-left: 14px;
	background-repeat: no-repeat;
	
	-webkit-background-size: 12px 16px;
	-moz-background-size: 12px 16px; 
	background-size: 12px 16px;
}

/* entity listings */
.embed_data .elgg-image img {
	cursor: pointer;
}
.embed_data .elgg-image-block  {
	border-bottom:none;
}
.embed_modal_videolist .elgg-image img {
	width:75px;
	height:auto;
}
.embed_modal_document .embed_data .elgg-body {
	margin-top:0;
	margin-left:7px;
}
.embed_modal_tidypics .embed_data .elgg-body {
	margin-top:11px;
}
.embed_modal_file .embed_data .elgg-body {
	margin-top:0px;
	margin-left:9px;
}
.embed_data .elgg-body {
	width:auto;
	margin-top:15px;
	margin-left:15px;
}
.embed_data .elgg-image-block {
	cursor:pointer;
}

.elgg-menu-longtext {
    display:none;
}