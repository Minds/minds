<?php
/**
 * CSS typography
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style>
/* ***************************************
	Typography
*************************************** */
body {
	font-size: 11px;
	font-family: "Lucida Grande", Tahoma, Verdana, Arial, sans-serif;
	color: #333;
}
a {
	color: #3B5998
}

pre, code {
	font-family: Monaco, "Courier New", Courier, monospace;
	font-size: 12px;
	background:#EBF5FF;
	color:#000000;
	overflow:auto;

	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */

	white-space: pre-wrap;
	word-wrap: break-word; /* IE 5.5-7 */
}
blockquote {
	line-height: 1.3em;
}
h1, h2, h3, h4, h5, h6 {
	font-weight: bold;
	color: #333;
}
h1 { font-size: 1.8em; }
h2 { font-size: 1.5em; line-height: 1.1em; padding-bottom:5px; }
h3 { font-size: 1.2em; }
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }

a {
	color: #3B5998;
	cursor:pointer;
}
a:hover {
	text-decoration: underline;
}

p {
	margin-bottom: 15px;
}
p:last-child {
	margin-bottom: 0;
}

dt {
	font-weight: bold;
}
dd {
	margin: 0 0 1em 1em;
}
code {
	padding:2px 3px;
}
pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}
blockquote {
	padding:3px 15px;
	margin:0px 0 15px 0;
	background:#EBF5FF;
	border:none;

	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

.elgg-monospace {
	font-family: Monaco, "Courier New", Courier, monospace;
}

.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	text-shadow: 0 0 1px #627AAD;
	text-decoration: none;
}
.elgg-heading-main {
	float: left;
	max-width: 530px;
	margin-right: 10px;
}
.elgg-heading-basic {
	color: #0054A7;
	font-size: 1.2em;
	font-weight: bold;
}

.elgg-subtext {
	color: #666666;
}

/* ***************************************
	USER INPUT DISPLAY RESET
*************************************** */
.elgg-output ul, ol {
	margin: 0 1.5em 1.5em 0;
	padding-left: 1.5em;
}
.elgg-output ul {
	list-style-type: disc;
}
.elgg-output ol {
	list-style-type: decimal;
}
.elgg-output table {
	border: 1px solid #ccc;
}
.elgg-output table td {
	border: 1px solid #ccc;
	padding: 3px 5px;
}
.elgg-output img {
	max-width: 100%;
}