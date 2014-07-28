<?php
/**
 * CSS typography
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>

/* ***************************************
	Typography
*************************************** */
@font-face {
    font-family: 'entypo';
    src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.eot?') format('eot'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.woff') format('woff'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.ttf') format('truetype'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.svg') format('svg');
    font-weight: normal;
    font-style: normal;
}
@font-face {
  font-family: 'fontello';
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?96059246');
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?96059246#iefix') format('embedded-opentype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.woff?96059246') format('woff'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.ttf?96059246') format('truetype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.svg?96059246#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}
@font-face {
  font-family: 'Ubuntu';
  font-style: normal;
  font-weight: 300;
  src: local('Ubuntu Light'), local('Ubuntu-Light'), url(https://themes.googleusercontent.com/static/fonts/ubuntu/v4/_aijTyevf54tkVDLy-dlnLO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
}
@font-face {
  font-family: 'Lato';
  font-style: normal;
  font-weight: 300;
  src: local('Lato Regular'), local('Lato-Regular'), url(https://themes.googleusercontent.com/static/fonts/lato/v7/9k-RPmcnxYEPm8CNFsH2gg.woff) format('woff');
}
.entypo{
	font-family:'fontello', 'Ubuntu', Tahoma, sans-serif;
	font-size:18px;
	font-weight:normal;
	text-decoration:none;
	vertical-align: middle;
}

body {
	font-size: 80%;
	line-height: 1.4em;
	font-family: Helvetica, arial, clean, sans-serif;
}

a {
	color: #4690D6;
}

a:hover,
a.selected { <?php //@todo remove .selected ?>
	color: #555;
	text-decoration: underline;
}

p {
	text-rendering: auto;
	margin-bottom: 15px;
	font-size: 14px;
	line-height: 28px;
	font-weight: Lighter;
}

p:last-child {
	margin-bottom: 0;
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

pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}

code {
	padding:2px 3px;
}

.elgg-monospace {
	font-family: Monaco, "Courier New", Courier, monospace;
}

blockquote {
	line-height: 1.3em;
	padding:3px 15px;
	margin:0px 0 15px 0;
	background:#EBF5FF;
	border:none;
	
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
}

h1, h2, h3, h4, h5, h6 {
	font-family:"Ubuntu";
	font-weight:lighter;
	color: #333;
}

h1 { font-size: 1.8em; }
h2 { font-size: 42px; line-height: 1.1em; padding-bottom:5px}
h3 { font-size: 28px; line-height: 28px;}
h4 { font-size: 1.0em; }
h5 { font-size: 0.9em; }
h6 { font-size: 0.8em; }

h3 a{ color:#333; }

.elgg-heading-site, .elgg-heading-site:hover {
	font-size: 2em;
	line-height: 1.4em;
	color: white;
	font-style: italic;
	font-family: Georgia, times, serif;
	text-shadow: 1px 2px 4px #333333;
	text-decoration: none;
}
.owner_block .text h3{
	font-size:18px;
}
	
.heading-main, .elgg-heading-main {
	float: left;
	max-width: 100%;
	padding: 8px;
	font-size: 32px;
	font-weight: lighter;
}
.elgg-heading-basic {
	color: #333;
	font-size: 1.2em;
	font-weight: bold;
}

.subtext, .elgg-subtext {
	/* clear: both; */
padding: 0 8px 8px;
color: #666666;
font-size: 85%;
line-height: 1.2em;
font-style: italic;
float: left;
}

.elgg-text-help {
	display: block;
	font-size: 85%;
	font-style: italic;
}

.elgg-quiet {
	color: #666;
}

.elgg-loud {
	color: #333;
}

/* ***************************************
	USER INPUT DISPLAY RESET
*************************************** */
.elgg-output {
	margin: 10px 0;
}

.elgg-output dt { font-weight: bold }
.elgg-output dd { margin: 0 0 1em 1em }

.elgg-output ul, .elgg-output ol {
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
	height:auto;
}

.elgg-image-block .elgg-body h3{
	font-size: 20px;
	line-height: 20px;
}
