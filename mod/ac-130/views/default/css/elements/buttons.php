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

/* Base ********************/
.elgg-button,
.elgg-button:hover {
	font-family: "Trebuchet MS", Arial, Tahoma, Verdana, sans-serif;
	font-size: 12px;
	font-weight: bold;
	line-height: 1.5em;
	
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;

	width: auto;
	padding: 5px 15px;
	cursor: pointer;
	outline: none;
  text-decoration: none;
	
	-webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.5);
	box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.5);

  color: #222;
	text-shadow: 0px 1px 1px #fff;
  border: 1px solid #888;
	background: #fff url(<?php echo elgg_get_site_url(); ?>mod/ac-130/images/button.png) repeat-x left top;
}

.elgg-button:hover {
  border-color: #666;
	background-color: #ddd;
}

.elgg-button:active {
	background-image: url(<?php echo elgg_get_site_url(); ?>mod/ac-130/images/button-active.png);
  background-position: left bottom;

	-webkit-box-shadow: inset 0px 1px 2px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: inset 0px 1px 2px rgba(0, 0, 0, 0.5);
	box-shadow: inset 0px 1px 2px rgba(0, 0, 0, 0.5);
}

.elgg-button.elgg-state-disabled,
.elgg-button.elgg-state-disabled:hover {
	background: #999;
	border-color: #999;
	cursor: default;
	color: #222;
	text-shadow: 0px 1px 1px #fff;
	-webkit-box-shadow: none;
	-moz-box-shadow: none;
	box-shadow: none;
}


/* Submit ********************/
.elgg-button-submit, 
.elgg-button-submit:hover {
	color: #fff;
	text-shadow: 0px 1px 1px #000;
	text-decoration: none;
  border-color: #048;
	background-color: #28f;
}

.elgg-button-submit:hover {
  border-color: #98CF00;
	background-color: #98CF00;
}


/* Cancel ********************/
.elgg-button-cancel, 
.elgg-button-cancel:hover {
	color: #fff;
	text-shadow: 0px 1px 1px #000;
	text-decoration: none;
	border-color: #222;
	background-color: #666;
}

.elgg-button-cancel:hover {
  border-color: #000;
	background-color: #444;
}


/* Delete ********************/
.elgg-button-delete,
.elgg-button-delete:hover {
	color: #fff;
	text-shadow: 0px 1px 1px #000;
	text-decoration: none;
	border-color: #800;
	background-color: #d22;
}

.elgg-button-delete:hover {
  border-color: #600;
	background-color: #b00;
}


/* Special ********************/
.elgg-button-special,
.elgg-button-special:hover {
	color: #fff;
	text-shadow: 0px 1px 1px #000;
	text-decoration: none;
	border-color: #080;
	background-color: #2d3;
}

.elgg-button-special:hover {
  border-color: #060;
	background-color: #0c1;
}


/* Dropdown ********************/
.elgg-button-dropdown,
.elgg-button-dropdown:hover,
.elgg-button-dropdown.elgg-state-active {
  border: 0;
}

.elgg-button-dropdown:after {
	content: " \25BC ";
	font-size: smaller;
}

.elgg-button-dropdown.elgg-state-active {
	background: #ccc url(<?php echo elgg_get_site_url(); ?>mod/ac-130/images/button-active.png) repeat-x left bottom;
  -webkit-border-bottom-right-radius: 0;
  -webkit-border-bottom-left-radius: 0;
  -moz-border-radius-bottomright: 0;
  -moz-border-radius-bottomleft: 0;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
