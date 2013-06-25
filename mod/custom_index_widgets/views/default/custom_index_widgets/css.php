<?php
	/**
	 * Custom Index page css extender
	 * 
	 * @package custom_index
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */
?>

.icon_members {
	float:left;
	margin:2pt 5px 3px 0pt;
}

.icon_latest {
	margin:0 auto;
}
#login-box{
	width:100%;
}
#login-box form{
	width:auto;
}

#login-box input[type="text"],
#login-box input[type="password"]{
	width: 95%;
}

#rightcolumn_widgets, 
#leftcolumn_widgets, 
#middlecolumn_widgets{
	min-height: 1px;
}


#rightcolumn_widgets.small_edit_mode_box, 
#leftcolumn_widgets.small_edit_mode_box, 
#middlecolumn_widgets.small_edit_mode_box,
#customise_page_view table tr td h2.small_edit_mode_box { 
  margin:5px 10px 0 0;
  width: auto;
}
#rightcolumn_widgets.medium_edit_mode_box, 
#leftcolumn_widgets.medium_edit_mode_box, 
#middlecolumn_widgets.medium_edit_mode_box,
#customise_page_view table tr td h2.medium_edit_mode_box{
 margin:5px 10px 0 0;
  width: auto;
}
#rightcolumn_widgets.big_edit_mode_box, 
#leftcolumn_widgets.big_edit_mode_box, 
#middlecolumn_widgets.big_edit_mode_box,
#customise_page_view table tr td h2.big_edit_mode_box{ 
 margin:5px 10px 0 0;
  width: auto;
}
#rightcolumn_widgets.half_edit_mode_box, 
#leftcolumn_widgets.half_edit_mode_box, 
#middlecolumn_widgets.half_edit_mode_box,
#customise_page_view table tr td h2.half_edit_mode_box{ 
  margin:5px 10px 0 0;
  width: auto;
}

#rightcolumn_widgets.small_index_mode_box, 
#leftcolumn_widgets.small_index_mode_box, 
#middlecolumn_widgets.small_index_mode_box,
#customise_page_view table tr td h2.small_index_mode_box { 
  /*width: 312px;*/
  width: auto;
  padding: 0 0 5px;
  margin-right: 10px;
  border: 0 none;
}
#rightcolumn_widgets.medium_index_mode_box, 
#leftcolumn_widgets.medium_index_mode_box, 
#middlecolumn_widgets.medium_index_mode_box,
#customise_page_view table tr td h2.medium_index_mode_box{
  /*width: 608px;*/
  width: auto;
  padding: 0 0 5px;
  margin-right: 10px;
  border: 0 none;
}
#rightcolumn_widgets.big_index_mode_box, 
#leftcolumn_widgets.big_index_mode_box, 
#middlecolumn_widgets.big_index_mode_box,
#customise_page_view table tr td h2.big_index_mode_box{ 
  width: auto;
  padding: 0 0 5px;
  margin-right: 10px;
  border: 0 none;
}
#rightcolumn_widgets.half_index_mode_box, 
#leftcolumn_widgets.half_index_mode_box, 
#middlecolumn_widgets.half_index_mode_box,
#customise_page_view.half_index_mode_box h2{ 
  /*width: 460px;*/
  width: auto;
  padding: 0 0 5px;
  margin-right: 10px;
  border: 0 none;
}

table.index_mode{
  width: 99%;
  border: 0 none;
  margin: 20px 0;
}

td.small {
  width: 38%;
}
td.half {
  width: 47%;
}
td.medium {
  width: 57%;
}
td.big {
  width: 100%;
}
.logintop{
	margin:0 auto;
	padding:0;
	padding-top: 3px;
	width:990px;
}
.logintop_links{
	margin-left:80px;
}

.logintop_links a {
	margin:0 0 0 2px;
	color:#999999;
	padding:3px;
}
.logintop_links a:hover {
	color:#eeeeee;
}

#logintopform{
	color: #BBBBBB;
	font-size: 12px;
}

#logintopform input.logintop_input {
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	background-color:#FFFFFF;
	border:1px solid #BBBBBB;
	color:#999999;
	font-size:12px;
	font-weight:bold;
	margin:0pt;
	padding:2px;
	width:180px;
	height:12px;
}
#logintopform input.logintop_submit_button {
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	color:#333333;
	background: #cccccc;
	border:none;
	font-size:12px;
	font-weight:bold;
	margin:0px;
	padding:2px;
	width:auto;
	height:18px;
	cursor:pointer;
}
#logintopform input.logintop_submit_button:hover {
	color:#ffffff;
	background: #4690d6;
}

/* ***************************************
STANDARD BOXES
*************************************** */
.standard_box {
	margin: 0 0 20px 0;
	height:auto;

}
/* IE6 fix */
* html .standard_box  {
	height:10px;
}
.standard_box_header {
	color: #4690d6;
	padding: 5px 10px 5px 10px;
	margin:0;
	border-left: 1px solid white;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	-webkit-border-top-right-radius:8px;
	-webkit-border-top-left-radius:8px;
	background:#dedede;
}
.standard_box_header h1 {
	color: #0054a7;
	font-size:1.25em;
	line-height: 1.2em;
}
.standard_box_content {
	padding: 10px 0 10px 0;
	margin:0;
	height:auto;
	background:#dedede;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-webkit-border-bottom-right-radius:8px;
	-webkit-border-bottom-left-radius:8px;
	border-left: 1px solid white;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
}
.standard_box_content .contentWrapper {
	margin-bottom:5px;
}
.standard_box_editpanel {
	display: none;
	background: #a8a8a8;
	padding:10px 10px 5px 10px;
	border-left: 1px solid white;
	border-bottom: 1px solid white;
}
.standard_box_editpanel p {
	margin:0 0 5px 0;
}
.standard_box_header a.toggle_box_contents {
	color: #4690d6;
	cursor:pointer;
	font-family: Arial, Helvetica, sans-serif;
	font-size:20px;
	font-weight: bold;
	text-decoration:none;
	float:right;
	margin: 0;
	margin-top: -7px;
}
.standard_box_header a.toggle_box_edit_panel {
	color: #4690d6;
	cursor:pointer;
	font-size:9px;
	text-transform: uppercase;
	text-decoration:none;
	font-weight: normal;
	float:right;
	margin: 3px 10px 0 0;
}
.standard_box_editpanel label {
	font-weight: normal;
	font-size: 100%;
}


/* ***************************************
PLAIN BOXES
*************************************** */
.plain_box , .plain.collapsable_box{
	margin: 0 0 20px 0;
	height:auto;

}
/* IE6 fix */
* html .plain_box , * html .plain.collapsable_box {
	height:10px;
}
.plain_box_header , .plain.collapsable_box_header{
	color: #4690d6;
	padding: 5px 10px 5px 10px;
	margin:0;
	border-left: 1px solid #cccccc;
	border-right: 1px solid #cccccc;
	border-top: 1px solid #cccccc;
	-moz-border-radius-topleft:8px;
	-moz-border-radius-topright:8px;
	-webkit-border-top-right-radius:8px;
	-webkit-border-top-left-radius:8px;
	background:transparent;
}
.plain_box_header h1, .plain.collapsable_box_header h1 {
	color: #0054a7;
	font-size:1.25em;
	line-height: 1.2em;
}
.plain_box_content, .plain.collapsable_box_content {
	padding: 10px 0 10px 0;
	margin:0;
	height:auto;
	-moz-border-radius-bottomleft:8px;
	-moz-border-radius-bottomright:8px;
	-webkit-border-bottom-right-radius:8px;
	-webkit-border-bottom-left-radius:8px;
	border-left: 1px solid #cccccc;
	border-right: 1px solid #cccccc;
	border-bottom: 1px solid #cccccc;
	background:transparent;
	
}
.plain_box_content .contentWrapper , .plain.collapsable_box_content .contentWrapper{
	margin-bottom:5px;
}
.plain_box_editpanel .plain.collapsable_box_editpanel{
	display: none;
	background: #a8a8a8;
	padding:10px 10px 5px 10px;
	border-left: 1px solid white;
	border-bottom: 1px solid white;
}
.plain_box_editpanel p , .plain.collapsable_box_editpanel{
	margin:0 0 5px 0;
}
.plain_box_header a.toggle_box_contents , .plain.collapsable_box_header a.toggle_box_contents{
	color: #4690d6;
	cursor:pointer;
	font-family: Arial, Helvetica, sans-serif;
	font-size:20px;
	font-weight: bold;
	text-decoration:none;
	float:right;
	margin: 0;
	margin-top: -7px;
}
.plain_box_header a.toggle_box_edit_panel , .plain.collapsable_box_header a.toggle_box_edit_panel {
	color: #4690d6;
	cursor:pointer;
	font-size:9px;
	text-transform: uppercase;
	text-decoration:none;
	font-weight: normal;
	float:right;
	margin: 3px 10px 0 0;
}
.plain_box_editpanel label , .plain.collapsable_box_editpanel label{
	font-weight: normal;
	font-size: 100%;
}
