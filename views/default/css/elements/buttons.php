<?php
/**
 * CSS buttons
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* **************************
	BUTTONS
************************** */

/* Base */
.elgg-button {
	font-size: 12px;
	font-weight: bold;
	text-align: left;
	
	-webkit-border-radius: 2px;
	-moz-border-radius:2px;
	border-radius:2px;

	min-width:125px;
	width: auto;
	padding: 8px 12px;
	cursor: pointer;
	outline: none;
	
	-webkit-box-shadow:0;
	-moz-box-shadow: 0;
	box-shadow: 0;
}
a.elgg-button {
	padding: 8px 12px;
}

/* Submit: This button should convey, "you're about to take some definitive action" */
.elgg-button-submit {
	min-width:0;
	background: #EEE;
	background:linear-gradient(#FCFCFC, #EEEEEE);
	border:1px solid #CCC;
	color: #333;
	text-decoration: none;
	text-shadow: 0;
	cursor: pointer;
}

.elgg-button-submit:hover {
	text-decoration: none;
	background: #EEE;
}

.elgg-button-submit.elgg-state-disabled {
	background: #999;
	border-color: #999;
	cursor: default;
}

/* Cancel: This button should convey a negative but easily reversible action (e.g., turning off a plugin) */
.elgg-button-cancel {
	color: #FFF;
	background: #FF0A0A;
}
.elgg-button-cancel:hover {
	color:#FFF;
	background-color: #E00A0A;
	text-decoration: none;
}

/* Action: This button should convey a normal, inconsequential action, such as clicking a link */
.elgg-button-action {
	background: #4690D6;
	border:1px solid #4690D6;
	color: #FFF;
	text-decoration: none;
	text-shadow: 0;
	cursor: pointer;
}

.elgg-button-action:hover,
.elgg-button-action:focus {
	color: #FFF;
	text-decoration: none;
	background:#4690C3;
	border:1px solid #4690C3;
}

/* Delete: This button should convey "be careful before you click me" */
.elgg-button-delete {
	color: #bbb;
	text-decoration: none;
	border: 1px solid #333;
	background: #555 url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
	text-shadow: 1px 1px 0px black;
}
.elgg-button-delete:hover {
	color: #999;
	background-color: #333;
	background-position: left 10px;
	text-decoration: none;
}

.elgg-button-dropdown {
	padding:3px 6px;
	text-decoration:none;
	display:block;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color: white;
	border:1px solid #71B9F7;
	
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
	
	-webkit-box-shadow: 0 0 0;
	-moz-box-shadow: 0 0 0;
	box-shadow: 0 0 0;
	
	/*background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-position:-150px -51px;
	background-repeat:no-repeat;*/
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size:smaller;
}

.elgg-button-dropdown:hover {
	background-color:#EEE;
	text-decoration:none;
}

.elgg-button-dropdown.elgg-state-active {
	background: #ccc;
	outline: none;
	color: #333;
	border:1px solid #ccc;
	
	-webkit-border-radius:4px 4px 0 0;
	-moz-border-radius:4px 4px 0 0;
	border-radius:4px 4px 0 0;
}

/**
 * Minds
 */
.minds-button-launch {
	background:linear-gradient(#378316,#338316);
	border:1px solid #378316;
	color:#FFF;
}
.minds-button-launch:hover {
	background:#308016;
	text-decoration:none;
	color:#FFF;
}
.minds-button-register {
	min-width:0;
	background: #4690D6;
	background:linear-gradient(#489BEC, #4690C3);
	border:1px solid #4690D6;
	color: #FFF;
	text-decoration: none;
	text-shadow: 0;
	cursor: pointer;
}

.minds-button-register:hover{
	color: #FFF;
	text-decoration: none;
	background:#4690C3;
	
	border:1px solid #4690C3;
}

.minds-button-login {
	min-width:0;
	background: #EEE;
	background:linear-gradient(#FCFCFC, #EEEEEE);
	border:1px solid #CCC;
	color: #333;
	text-decoration: none;
	text-shadow: 0;
	cursor: pointer;
}

.minds-button-login:hover {
	text-decoration: none;
	background: #EEE;
}