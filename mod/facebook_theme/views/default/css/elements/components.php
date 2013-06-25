<?php
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 *
 * @package Elgg.Core
 * @subpackage UI
 */
/**
 * elgg-body fills the space available to it.
 * It uses hidden text to expand itself. The combination of auto width, overflow
 * hidden, and the hidden text creates this effect.
 *
 * This allows us to float fixed width divs to either side of an .elgg-body div
 * without having to specify the body div's width.
 *
 * @todo check what happens with long <pre> tags or large images
 * @todo Move this to its own file -- it is very complicated and should not have to be overridden.
 */
?>
/* <style>
/* ***************************************
	Image Block
*************************************** */
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 10px;
}
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 5px;
}

/* ***************************************
	List
*************************************** */
.elgg-list {
	clear: both;
}

.elgg-list > li {
	border-bottom: 1px solid #E9E9E9;
	padding: 5px 0;
}
.elgg-list > li:last-child {
	border-bottom: 0;
}

.elgg-list-item .elgg-subtext {
	margin-bottom: 5px;
}
.elgg-list-content {
	margin: 10px 5px;
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
.elgg-gallery-fluid li {
	float: left;
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
.elgg-table-alt td {
	padding: 2px 4px 2px 4px;
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
	margin-bottom: 20px;
}

/* ***************************************
	Messages
*************************************** */
.elgg-message {
	color: white;
	font-weight: bold;
	display: block;
	padding: 3px 10px;
	cursor: pointer;
	opacity: 0.9;

	-webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.45);

	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
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
.elgg-river > li {
	border-bottom: 1px solid #E9E9E9;
	padding: 10px 35px 10px 0;
}

.elgg-river-item {
	padding: 7px 0;
}

.elgg-river-timestamp {
	color: #888;
	margin-top: 3px;
	display: inline-block;
}

.elgg-river-message {
	color: #333;
	margin-top: 3px;
}

.elgg-river-summary {
	font-weight: normal;
	font-size: 11px;
	color: gray;
}

.elgg-river-subject,
.elgg-river-target {
	font-weight: bold;
}

.elgg-river-attachments {
	border-left: 2px solid #CCC;
	margin: 8px 0 5px 0;
	padding-left: 5px;
	color: #777;
}

/* The following styles produce the speech-bubble "nub" (triangle) */
.elgg-river-responses {
	position: relative;
	padding-top: 5px;
}

.elgg-river-responses:before {
	width: 0px;
	height: 0px;
	font-size: 0px;
	line-height: 0px;
	display: block;
	clear: both;
	content: " ";
	
	border-left: 5px solid transparent;
	border-right: 5px solid transparent;
	border-bottom: 5px solid #EDEFF4;
	
	position: absolute;
	top: 0px;
	left: 15px;
}
/* End speech bubble nub */

/* .elgg-river-responses > :not(ul, ol), */ /* <-- too advanced? */
.elgg-river-responses > div,
.elgg-river-responses > form,
.elgg-river-responses > ul > li {
	background-color: #EDEFF4;
	border-bottom: 1px solid #E5EAF1;
	margin-bottom: 2px;
	padding: 4px;
}

<?php //@todo location-dependent styles ?>
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

.elgg-river-comments {
	margin: 0;
	border-top: none;
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

/* ***************************************
	Tags
*************************************** */
.elgg-tags {
	display: inline;
	font-size: 85%;
}
.elgg-tags li {
	display: inline;
	margin-right: 5px;
}
.elgg-tags li:after {
	content: ",";
}
.elgg-tags li:last-child:after {
	content: "";
}
.elgg-tagcloud {
	text-align: justify;
}

/* MISCELLANEOUS */
.elgg-photo {
	border: 1px solid #ccc;
	padding: 3px;
	background-color: white;
}

.elgg-comments {
	margin-top: 25px;
}
.elgg-comments > form {
	margin-top: 15px;
}