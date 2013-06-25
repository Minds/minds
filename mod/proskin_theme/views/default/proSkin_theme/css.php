<?php 
/**
 * Fixes/tweaks
 */

?>
/* <style>
/**/
.elgg-icon {vertical-align:middle}
dl, dt, dd {margin:0;padding:0}

/* PROFILE */
.elgg-profile {
	display:block;
}

.elgg-profile > dt {
	float: left;
	width: 120px;
	font-weight:bold;
	color:#333;
	padding: 10px 0;
}
	
.elgg-profile > dd {
	padding: 10px 0 10px 120px;
}

.elgg-profile > dd ~ dd {
	border-top: 1px solid #E9E9E9;
}

.elgg-profile > dd + dd {
	padding-left: 0;
	margin-left: 120px;
}

img {max-width:100%}

#groups-tools > .elgg-module {
	width: 229px;
}

#facebook-topbar-logo {
	margin-top: -7px;
	font-size: 20px;
	color: black;
	text-shadow: 0px 0px 3px lightblack;
	width: 100px;
	text-align:center;
}

#facebook-header-logo a {
	color: black;
	text-decoration:none;
	font-size: 2.5em;
	margin-top: +15px;
}

.elgg-form-small input,
.elgg-form-small textarea {
	font-size: 11px;
}

.elgg-image-block-small > .elgg-image {
	margin-right: 5px;
}


/* NEW PAGE COMPONENT: COMPOSER */

.ui-tabs-hide {
	display:none;
}

.elgg-composer {
	border-top: 1px solid #b3b3b3;
	padding-top: 6px;
	margin-top: 7px;
}

.elgg-composer > h4 {
	height: 22px;
	display: inline-block;
	vertical-align: baseline;
	color: gray;
}

.elgg-composer > .ui-tabs-panel {
	margin-top: 5px;
	border: 1px solid #b3b3b3;
	padding: 10px;
}

.messageboard-input {
	margin-bottom: 5px;
}

.elgg-attachment-description {
	margin-top: 5px;
}

#thewire-form-composer #thewire-textarea {
	margin-top:0;
}

.messageboard-input {
	height: 60px;
}

#facebook-header-login {
	bottom: 25px;
	position: absolute;
	right: 0;
}

#facebook-header-login label {
	color:black;
	display: block;
	font-weight: normal;
	padding: 2px 2px 4px;
}

#facebook-header-login .elgg-foot > label {
	bottom: -16px;
	color:#333;
	cursor: pointer;
	left: 0;
	position: absolute;
}

#facebook-header-login div {
	display: inline-block;
	margin-bottom: 3px;
	padding-right: 10px;
}

#facebook-header-login .elgg-input-text,
#facebook-header-login .elgg-input-password {
	border-color:#000;
	color: black;
	font-size: 11px;
	margin:0;
	padding: 3px 3px 4px;
	width: 150px;
}

#facebook-header-login .elgg-menu {
	position: absolute;
	margin-left: -160px;
}

#facebook-header-login .elgg-menu > li {
	display: inline-block;
	margin-right: 10px;
}

#facebook-header-login .elgg-menu > li > a {
	color:#333;
	display: inline;
}

#facebook-header-login .elgg-menu > li > a:hover {
	text-decoration: underline;
}

#facebook-header-login .elgg-button-submit {
	position: relative; 
	top: 10px;
}


input[type="checkbox"] {
	vertical-align:bottom;
}