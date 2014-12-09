.elgg-list > li<?php
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	Image Block
*************************************** */
.elgg-image-block {
	padding: 12px 4px;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right:10px;	
}
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 5px;
}

/* ***************************************
	List
*************************************** */
.elgg-list {
	/*border-top: 1px dotted #CCCCCC;*/
	margin:5px  0;
	clear: both;
	width:100%;
}
.elgg-list > li {
	float: left;
	margin: 24px 0 0 2%;
    border: 0;
	background:#F8F8F8;
	/*padding:0 10px 10px 11px;*/
        width: 29%;
        /*width:28%;*/
	overflow: hidden;
	height:240px;
        display: block;
	position:relative;

	box-shadow: 0 0 1px #DDD;
	-webkit-box-shadow: 0 0 1px #DDD;
	-moz-box-shadow: 0 0 1px #DDD;
	border: 1px solid #DDD;
}
.elgg-list > li .elgg-image-block{
	padding:16px;
}
.elgg-list.x4 > li {
	width:22%;
	margin:8px 0 8px 2%;
}
.elgg-list.x1{
	width:600px;
	margin:auto;
} 
.elgg-list.x1 > li {
	float:none;
	width:100% !important;
	height:auto;
}
.elgg-list.no-margin, .elgg-list.no-margin > li{
	margin:0!important;
}
.elgg-list.x2{ 
	/*width:auto;
	margin:auto;
	-moz-column-count: 2 !important;
	-moz-column-gap: 10px;
	-webkit-column-count: 2 !important;
	-webkit-column-gap: 10px;*/
}
.elgg-list.x2 > li{

	float:left;
	width:40%;
	
	-webkit-transition-property: left,right,top;
    -moz-transition-property: left,right,top;
    -ms-transition-property: left,right,top;
    -o-transition-property: left,right,top;
    transition-property: left,right,top;
    -webkit-transition-duration: 0.6s;
    -moz-transition-duration: 0.6s;
    -ms-transition-duration: 0.6s;
    -o-transition-duration: 0.6s;
    transition-duration: 0.6s;
}

@media screen and (max-width: 1100px) {
    .elgg-list > li{
            width:28%;
            height:210px;
    }
}
@media screen and (min-width: 1400px) {
	
	.elgg-list > li{
		width:22%;
		height:210px;
	}
	.elgg-list.x4 > li {
		width:12.5%;
	}
}
@media screen and (min-width: 1800px) {

	 .elgg-list > li{
                width:18.5%;
                height:210px;
		margin:0 0 0 1%;
        }
}
/**
 * MASON PROTOTYPE
 */
.elgg-list.mason{

}
.elgg-list.mason > li{
	height: auto;
	display:inline-block;
}
.elgg-footer .elgg-list > li{
	width: 42%;
	margin: 16px 2%;
}

.elgg-list > li .elgg-avatar-medium{
	margin:10px 8px 0 5px;
}

/**
 * Vertical (1 across) listing
 */
.elgg-list.vertical-list{
	padding:0 !important;
	margin:0;
}
.elgg-list.vertical-list li{
	padding:16px;
	margin:8px 0;
	float:none;
	width:auto;
	height:auto;
	min-height:0;
}

.elgg-list-river{
	width:auto;
}

.elgg-item h2{
	font-size:16px;
}

.elgg-item i{
	color:#999;
	font-size:11px;
}

.elgg-item .elgg-subtext {
	margin-bottom: 5px;
}
.elgg-item .elgg-content {
	margin: 10px 5px;
}

.hz-list .elgg-list{
	width:auto;
	margin:0;
}
.hz-list .elgg-list .elgg-item{
	width:auto;
	height:auto;
	margin:10px 0;
	float:none;
}
/* ***************************************
	Gallery
*************************************** */
.elgg-gallery {
	border: none;
	margin-right: auto;
	margin-left: auto;
}
.elgg-gallery td {
	padding: 5px;
}
.elgg-gallery-fluid > li {
	float: left;
}
.elgg-gallery-users > li {
	margin: 0 1px;
}

/* ***************************************
	Tables
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table td, .elgg-table th {
	padding: 4px 8px;
	border: 1px solid #ccc;
}
.elgg-table th {
	background-color: #ddd;
}
.elgg-table tr:nth-child(odd), .elgg-table tr.odd {
	background-color: #fff;
}
.elgg-table tr:nth-child(even), .elgg-table tr.even {
	background-color: #f0f0f0;
}
.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #ccc;
}
.elgg-table-alt th {
	background-color: #eee;
	font-weight: bold;
}
.elgg-table-alt td, .elgg-table-alt th {
	padding: 4px;
	border-bottom: 1px solid #ccc;
}
.elgg-table-alt td:first-child {
	width: 200px;
}
.elgg-table-alt tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	Owner Block
*************************************** */
.elgg-owner-block {
	margin:8px;
	margin-bottom:0;
}
.elgg-owner-block .elgg-body > a > h2{
	font-size:20px;
	font-weight:normal;
	padding:0;
}
.elgg-owner-block .elgg-image img{
	width:100px;
}
.elgg-owner-block .channel-social-icons > a.entypo {
	font-size:20px;
}
/* ***************************************
	Messages
*************************************** */
.elgg-message {
	color: white;
	font-weight: bold;
	display: block;
	cursor: pointer;
	opacity: 0.9;
	
	padding: 16px;
	
	/*
	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px; */
}
.elgg-state-success {
	background-color: black;
}
.elgg-state-error {
	background-color: red;
}
.elgg-state-notice {
	background-color: #4690D6;
}

/* ***************************************
	River
*************************************** */
.minds-list-river, .elgg-list-river {
}
.minds-list-river > li, .elgg-list-river > li {
/*	padding:0;*/
	padding-bottom:0;
}
.minds-list-river > li > .minds-river-header, .elgg-list-river > li > .minds-river-header {
	/*padding:12px;*/
}
.minds-list-river > li > .minds-menu, .elgg-list-river > li > .elgg-menu {
	padding:12px;
	display:none;
	background:#FAFAFA;
}
.minds-list-river > li:hover > .minds-menu, .elgg-list-river > li:hover > .elgg-menu {
	display:block;
}
.minds-list-river > li .river-subject{
	font-weight:bold;
	font-size:13px;
}
.elgg-river-item .elgg-pict {
	margin-right: 20px;
}
.river-timestamp {
	color: #666;
	font-size: 85%;
	font-style: italic;
	line-height: 1.2em;
}

.minds-river-attachments,
.minds-river-message,
.minds-river-content {
	line-height: 1.5em;
	padding:0;
}
.minds-river-attachments img{
	width:100%;
}
.minds-river-attachments > p,
.minds-river-message > p,
.minds-river-content > p {
	padding:8px 0;
}
.minds-river-attachments {
	margin:0 -11px;
}
.minds-river-attachments .river-attachment{
	padding: 16px;
	background: none repeat scroll 0% 0% #E1E1E1;
	margin: 16px;
	border: 1px solid #AAA;
}
.minds-river-attachments .river-attachment > a{
	color:#333;
	font-size:14px;
	font-weight:bold;
}
.minds-river-attachments .river-attachment > p{
	font-size:11px;
}
.minds-river-attachments > img{
	width:100%;
}
.minds-river-responses{
	margin:0 -11px;
}
.elgg-river-attachments .elgg-avatar,
.elgg-river-attachments .elgg-icon {
	float: left;
}
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

.elgg-river-comments-tab {
	display: block;
	background-color: #EEE;
	color: #4690D6;
	margin-top: 5px;
	width: auto;
	float: right;
	font-size: 85%;
	padding: 1px 7px;
	
	-webkit-border-radius: 5px 5px 0 0;
	-moz-border-radius: 5px 5px 0 0;
	border-radius: 5px 5px 0 0;
}

.elgg-river-responses{
	margin-bottom:6px;
}

<?php //@todo components.php ?>
.elgg-river-comments {
	margin: 0;
	border-top: none;
}
.elgg-river-comments li:first-child {
	-webkit-border-radius: 5px 0 0;
	-moz-border-radius: 5px 0 0;
	border-radius: 5px 0 0;
}
.elgg-river-comments li:last-child {
	-webkit-border-radius: 0 0 5px 5px;
	-moz-border-radius-bottomleft: 0 0 5px 5px;
	border-radius-bottomleft: 0 0 5px 5px;
}
.elgg-river-comments li {
	background-color: #EEE;
	border-bottom: none;
	padding: 4px;
	margin-bottom: 2px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
	background-color: #EEE;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	padding: 2px 4px;
	font-size: 85%;
	margin-bottom: 2px;
}

<?php //@todo location-dependent styles ?>
.elgg-river-item form {
	background-color: #EEE;
	padding: 4px;
	
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	
	height: 30px;
}
.elgg-river-item input[type=text] {
	width: 80%;
}
.elgg-river-item input[type=submit] {
	margin: 0 0 0 10px;
}


/* **************************************
	Comments (from elgg_view_comments)
************************************** */
.elgg-comments {
	margin-top: 25px;
}
.elgg-comments > form {
	margin-top: 15px;
}

/* ***************************************
	Image-related
*************************************** */
.elgg-photo {
	border: 1px solid #ccc;
	padding: 3px;
	background-color: white;
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


/**
 * Newsfeed lists
 */
.list-newsfeed{
	padding:0 !important;
}
.list-newsfeed > li{
	float:none;
	margin:16px 0;
	width:auto;
	min-width:0;
	min-height:0;
	padding:0;
	height:auto;
	font-size:14px;
	line-height:20px;
}
.list-newsfeed > li .inner{
	margin:8px;
}
.list-newsfeed li > .inner > .elgg-body{
	overflow:visible;
}
.list-newsfeed > li .head{
	margin: 0px;
	display: block;
	overflow: hidden;
	height: 50px;
}
.list-newsfeed > li .head a{
	font-weight:bold;
	font-size:14px;
	display:block;
}
.list-newsfeed > li .head a .username{
	font-size:11px; 
	color:#888;
}
.list-newsfeed li .head .elgg-friendlytime{
	font-size:11px;
	color:#888;
}
.list-newsfeed li .activity-rich-post{
	margin:8px 0 0;
	padding:12px;
	border:1px solid #EEE;
	background:#FFF;
}
.list-newsfeed li .activity-rich-post a{
	color:#999;
}
.list-newsfeed li .activity-rich-post a:hover{
	text-decoration:none;
}
.list-newsfeed li .activity-rich-post h3{
	font-family:Georgia, Sans-Serif;	
}
.list-newsfeed li .activity-rich-post p{
	margin-bottom:0;
	padding-top:8px;
	line-height:14px;
	font-size:11px;
}
.list-newsfeed li .activity-rich-post .url{
	font-size:11px;
	font-weight:bold;
}
.list-newsfeed li  .activity-rich-post .thumbnail-wrapper{
	display: block;
	position: relative;
	max-height: 400px;
	overflow: hidden;
	width:110%;
	margin: -12px -5% 12px;
	padding: 0;
}
.list-newsfeed li  .activity-rich-post .thumbnail-wrapper img.thumbnail{
	display: block;
	max-height: none;
	vertical-align: top;
	width: 100%;
}
.list-newsfeed li .activity-remind{
	border:1px solid #DDD;
	background:#FFF;
	margin:16px;
}
