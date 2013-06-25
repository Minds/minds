<?php
/**
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style>
/* **************************
	BUTTONS
************************** */

.elgg-button + .elgg-button {
	margin-left: 4px;
}

/* Base */
.elgg-button {
	color: #333;
	font-weight: bold;
	text-decoration: none;
	width: auto;
	margin: 0;
	font-size: 11px;
	line-height: 16px;
	
	padding: 2px 6px;
	cursor: pointer;
	outline: none;
	text-align: center;
	white-space: nowrap;

	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #fff;

	border: 1px solid #999;
	border-bottom-color: #888;

    background: #eee;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f6f6), to(#e4e4e3));
    background: -moz-linear-gradient(#f5f6f6, #e4e4e3);
    background: -o-linear-gradient(#f5f6f6, #e4e4e3);
    background: linear-gradient(#f5f6f6, #e4e4e3);
}

.elgg-button:hover {
	color:#333;
	text-decoration:none;
}

.elgg-button:active {
	background: #ddd;
	border-bottom-color:#999;
	
	box-shadow: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}

.elgg-button.elgg-state-disabled {
	background: #F2F2F2;
	border-color: #C8C8C8;
	color: #B8B8B8;
	cursor: default;
	
	box-shadow: none;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
}

/* Submit: This button should convey, "you're about to take some definitive action" */
.elgg-button-submit {
	color: #fff !important;
    background: #5B74A8;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#637bad), to(#5872a7));
    background: -moz-linear-gradient(#637bad, #5872a7);
    background: -o-linear-gradient(#637bad, #5872a7);
    background: linear-gradient(#637bad, #5872a7);
	border-color: #29447E #29447E #1A356E;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #8a9cc2;
	
}

.elgg-button-submit:active {
	background: #4f6aa3;
	border-bottom-color: #29447e;
}

.elgg-button-submit.elgg-state-disabled {
	background: #ADBAD4;
	border-color: #94A2BF;
}


/* Delete: This button should convey "be careful before you click me" */
.elgg-button-delete {
	background: #444;
	border: 1px solid #333;
	color: #eee !important;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #999;
}

.elgg-button-delete:active {
	background: #111;
}

.elgg-button-delete.elgg-state-disabled {
	background: #999;
	border-color: #888;
}

/* Special: This button should convey "please click me!" */
.elgg-button-special {
	color:white !important;
    background: #69a74e;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#75ae5c), to(#67a54b));
    background: -moz-linear-gradient(#75ae5c, #67a54b);
    background: -o-linear-gradient(#75ae5c, #67a54b);
    background: linear-gradient(#75ae5c, #67a54b);
	border-color: #3b6e22 #3b6e22 #2c5115;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #98c286;
}

.elgg-button-special:active {
	background:#609946;
	border-bottom-color:#3b6e22;
}

.elgg-button-special.elgg-state-disabled {
	background: #B4D3A7;
	border-color: #9DB791;
}

/* Other button modifiers */
.elgg-button-dropdown {
	color: white;
	border:1px solid #71B9F7;
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size: smaller;
}

.elgg-button-dropdown:hover {
	background-color:#71B9F7;
}

.elgg-button-dropdown.elgg-state-active {
	background: #ccc;
	color: #333;
	border:1px solid #ccc;
}

.elgg-button-large {
	font-size: 13px;
	line-height: 19px;
}