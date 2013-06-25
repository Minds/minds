<?php
/**
 * Elgg Peek a boo theme
 * @package Peek a boo theme
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Web Intelligence
 * @copyright Web Intelligence
 * @link www.webintelligence.ie
 * @version 1.8
 */
?>
/* **************************
	BUTTONS
************************** */

.make-it-button a{
	font-family: Arial, Helvetica, sans-serif;
	background-color: #83CAFF;
	color: #FFF;
<!--	background: -moz-linear-gradient(
		top,
		#444444 0%,
		#000000);
	background: -webkit-gradient(
		linear, left top, left bottom, 
		from(#444444),
		to(#000000));-->
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	//border: 4px solid #83c9ff;
<!--	-moz-box-shadow:
		0px 1px 3px rgba(000,008,119,0.5),
		inset 0px 0px 1px rgba(255,255,255,1);
	-webkit-box-shadow:
		0px 1px 3px rgba(000,008,119,0.5),
		inset 0px 0px 1px rgba(255,255,255,1);
-->
	display: block;
	float:left;
	padding:5px 10px 5px 10px;
    text-decoration: none;
}

.make-it-button a:hover{
    background-color: #AEDF06;
}

/* Base */
.elgg-button {
	font-size: 14px;
	font-weight: bold;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;

	width: auto;
	padding: 2px 4px;
	cursor: pointer;
	outline: none;
	

}
a.elgg-button {
	padding: 3px 6px;
}

/* Submit: This button should convey, "you're about to take some definitive action" */
.elgg-button-submit {
	color: white;
	text-decoration: none;
	background: #83CAFF ;
        -webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
        border: 1px solid #83CAFF;
}

.elgg-button-submit:hover {
	text-decoration: none;
	color: white;
	background: #1054a7;
        border: 1px solid #1054a7;
}

.elgg-button-submit .elgg-state-disabled {
	background: #999;
	border-color: #999;
	cursor: default;
}

/* Cancel: This button should convey a negative but easily reversible action (e.g., turning off a plugin) */
.elgg-button-cancel {
	color: #333;
	background: #ddd url(<?php echo elgg_get_site_url(); ?>_graphics/button_graduation.png) repeat-x left 10px;
	border: 1px solid #999;
}
.elgg-button-cancel:hover {
	color: #444;
	background-color: #999;
	background-position: left 10px;
	text-decoration: none;
}

/* Action: This button should convey a normal, inconsequential action, such as clicking a link */
.elgg-button-action {
	background: #EEE; // url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif) repeat-x 0 0;
	border:2px solid #BBB;
	color: #999;
	padding: 2px 15px;
	text-align: center;
	font-weight: bold;
	text-decoration: none;
	text-shadow: 0 1px 0 #CCC;
	cursor: pointer;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}

.elgg-button-action:hover,
.elgg-button-action:focus {
	background: #DDD; // url(<?php echo elgg_get_site_url(); ?>_graphics/button_background.gif) repeat-x 0 -15px;
	color: #222;
	text-decoration: none;
	//border:2px solid #DDD; //#CEFF16;
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

	text-decoration:none;
	display:block;
	font-weight:bold;
	position:relative;
	margin-left:0;
	color: white;
	border:1px solid #83CAFF;
        background: #83CAFF;
        width:60px;
        height: 60px;
	
	-webkit-border-radius:4px;
	-moz-border-radius:4px;
	border-radius:4px;
	
	-webkit-box-shadow: 0 0 0;
	-moz-box-shadow: 0 0 0;
	box-shadow: 0 0 0;
	

}

.elgg-button-dropdown a.elgg-button{
        padding: 10px 10px;
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size:smaller;
}

.elgg-button-dropdown:hover {
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
