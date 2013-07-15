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
	color: #292929;
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

	border: 1px solid #424242;
	border-bottom-color: #313131;

    background: #626262;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#f5f6f6), to(#e4e4e3));
    background: -moz-linear-gradient(#f5f6f6, #e4e4e3);
    background: -o-linear-gradient(#f5f6f6, #e4e4e3);
    background: linear-gradient(#f5f6f6, #e4e4e3);
}

.elgg-button:hover {
	color:#313131;
	text-decoration:none;
}

.elgg-button:active {
	background: #5f5f5f;
	border-bottom-color:#404040;
	
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
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#9f9f9f), to(#7c7c7c));
    background: -moz-linear-gradient(#8c8c8c, #a4a4a4);
    background: -o-linear-gradient(#acacac, #5872a7);
    background: linear-gradient(#acacac, #5872a7);
	border-color: #5e5e5e #5e5e5e #13a3a3a;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c0c0c0;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c0c0c0;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c0c0c0;
	
}

.elgg-button-submit:active {
	background: #9e9e9e;
	border-bottom-color: #696969;
}

.elgg-button-submit.elgg-state-disabled {
	background: #cfcfcf;
	border-color: #b8b8b8;
}


/* Delete: This button should convey "be careful before you click me" */
.elgg-button-delete {
	background: #1b1b1b;
	border: 1px solid #2d2d2d;
	color: #5a5a5a !important;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #434343;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #434343;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #434343;
}

.elgg-button-delete:active {
	background: #111;
}

.elgg-button-delete.elgg-state-disabled {
	background: #434343;
	border-color: #383838;
}

/* Special: This button should convey "please click me!" */
.elgg-button-special {
	color:white !important;
    background: #a74e4e;
    background: -webkit-gradient(linear, 0 0, 0 100%, from(#ae5c5c), to(#a54b4b));
    background: -moz-linear-gradient(#ae5c5c, #67a54b);
    background: -o-linear-gradient(#ae5c5c, #67a54b);
    background: linear-gradient(#ae5c5c, #67a54b);
	border-color: #6e2222 #6e2222 #511515;
	-webkit-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c28686;
	-moz-box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c28686;
	box-shadow: 0 1px 0 rgba(0, 0, 0, 0.10), inset 0 1px 0 #c28686;
}

.elgg-button-special:active {
	background:#994646;
	border-bottom-color:#6e2222;
}

.elgg-button-special.elgg-state-disabled {
	background: #d3a7a7;
	border-color: #b79191;
}

/* Other button modifiers */
.elgg-button-dropdown {
	color: white;
	border:1px solid #c1c2c2;
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size: smaller;
}

.elgg-button-dropdown:hover {
	background-color:#a5a5a5;
}

.elgg-button-dropdown.elgg-state-active {
	background: #707070;
	color: #373737;
	border:1px solid #707070;
}

.elgg-button-large {
	font-size: 13px;
	line-height: 19px;
}