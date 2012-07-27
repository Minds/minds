<?php
$base_url = elgg_get_site_url();
$graphics_url = $base_url . 'mod/hypeFramework/graphics/';
echo elgg_view('css/hj/framework/icons');
echo elgg_view('css/hj/framework/elgg');
echo elgg_view('css/hj/framework/menu');
echo elgg_view('css/hj/framework/canvas');

//Third Parties
echo elgg_view('css/lightbox');
?>

.hj-left { float:left }
.hj-right { float:right }
.hj-left:after,
.hj-right:after {
content: ".";
display: block;
height: 0;
clear: both;
visibility: hidden;
}
.hj-padding-ten, .hj-admin-padding { padding:10px }
.hj-padding-fifteen { padding:15px }
.hj-padding-twenty { padding:20px }
.hj-margin-ten { margin:10px }
.hj-margin-fifteen { margin:15px }
.hj-margin-twenty { margin:20px }

.hidden { display:none }

.hj-content-container {
	background:#ffffff;
	width:auto;
	min-height:350px;
	margin:10px;
	padding-bottom:40px;
	border-width:1px;
	border-color:<?php echo $vars['hj_pallette_accessory'] ?>;
	border-style:solid;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}

.hj-ajax-loader {
	margin:0 auto;
	display:block;
}

.hj-loader-circle {
	background:transparent url(<?php echo $graphics_url ?>loader/circle.gif) no-repeat center center;
	width:75px;
	height:75px;
}
.hj-loader-bar {
	background:transparent url(<?php echo $graphics_url ?>loader/bar.gif) no-repeat center center;
	width:16px;
	height:11px;
}
.hj-loader-arrows {
	background:transparent url(<?php echo $graphics_url ?>loader/arrows.gif) no-repeat center center;
	width:16px;
	height:16px;
}
.hj-loader-indicator {
	background:transparent url(<?php echo $graphics_url ?>loader/indicator.gif) no-repeat center center;
	width:16px;
	height:16px;
}

div.mandatory {
	padding-right:15px;
	background:transparent url(<?php echo $graphics_url ?>mandatory.png) no-repeat 98% 23px;
}

input.hj-input-processing {
	background:white url(<?php echo $graphics_url ?>loader/indicator.gif) no-repeat right;
}

input.hj-input-processing:hover {
	background:white url(<?php echo $graphics_url ?>loader/indicator.gif) no-repeat right;
}

.hj-field-module {
    font-size:0.9em;
}

.hj-field-module-output {
    padding:5px 0;
    margin:0 10px;
    border-bottom:1px solid #f4f4f4;
}

.hj-output-label {
    font-style:italic;
    font-size:0.8em;
    vertical-align:top;
    display:inline-block;
    text-align:right;
    width:35%;
}

.hj-output-text {
    font-weight:bold;
    vertical-align:top;
    margin-left:10px;
    display:inline-block;
    text-align:left;
    width:58%;
}

.hj-icon-text {
    height:16px;
    display:inline-block;
    vertical-align:top;
    font-size:0.9em;
    margin-left:5px;
    margin-right:7px;
}

.elgg-list .elgg-module-aside .elgg-head {
padding-top:10px;
}
	.hj-view-list,
	.hj-view-list > li {
	border:none;
}

.elgg-menu > li > a.hidden {
	display:none;
}

.hj-active-temp {
	padding:10px;
	background:#f4f4f4;
}

.hj-gallery-view {
	max-width:620px;
	padding:5px;
	text-align:center;
	margin:0 auto;
}

.hj-file-icon-preview {
	margin:0 auto;
	text-align:center;
}

.hj-file-icon-master {
	
}

#hj-sortable-tabs > li:hover {
	cursor:move;
}
#hj-sortable-tabs > a:hover {
	cursor:pointer;
}

#fancybox-content > div > div.elgg-module {
    margin:0 20px;
    padding-bottom:100px;
}

.hj-pagination-next {
	padding: 3px;
	text-align: center;
	background: #F4F4F4;
	border-bottom: #E8E8E8 1px solid;
	color: #666;
	cursor: pointer;
	margin-top:10px;
}

/* ***************************************
	Tags
*************************************** */
.elgg-tags {
	font-size: 85%;
}
.elgg-tags > li {
	float:left;
	margin-right: 5px;
}
.elgg-tags li.elgg-tag:after {
	content: ",";
}
.elgg-tags li.elgg-tag:last-child:after {
	content: "";
}
.elgg-tagcloud {
	text-align: justify;
}
