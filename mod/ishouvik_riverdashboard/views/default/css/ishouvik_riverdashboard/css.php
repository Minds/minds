<?php

/**
 * iShouvik riverdashboard CSS
 * 
 * @author Shouvik Mukherjee <contact@ishouvik.com>
 * @copyright Shouvik Mukherjee 2012-2013
 * @link http://ishouvik.com/
 */

?>
.is_riverdash_right {
	width:200px;
	margin:0 0 20px 0;
	padding: 10px 0 0 0 !important;
	min-height:360px;
	float:right;
	padding:0;
}

.is_riverdash_left {
	width:200px;
	margin:0 0 20px 0px;
	padding: 10px 0 0 0 !important;
	min-height:360px;
	float:left;
	padding:0;
}
.is_riverdash_middle {
	float: right;
	width:500px !important;
	margin:0 0 20px 0px;
	padding: 10px 0 0 0;
	min-height:360px;
	float:left;
	padding:0 0 0 10px;
	border-left:2px solid #cccccc;
}

#dashboard1 {
	margin-bottom:20px;
}
#river_avatar {
	text-align:center;
	margin-bottom:20px;
	padding: 0;
	padding:3px;
	text-align:center;
}
river_avatar a img{
	height: 90%;
	width: 90%;
}
#lastloggedin {
	color:#777777;
    font-size:12px;
    font-weight:bold;
    text-align:center;
}
#dashboard_navigation {
	margin-top:20px;
    width:100%;
    font-size:12px;
}
#dashboard_navigation ul {
	margin:0 0 15px 0;
    padding:0;
}
#dashboard_navigation ul li {
	list-style:none;
	float:none;
	padding:5px;
    font-weight:bold;
    border-bottom:1px solid #cccccc;
    text-decoration:none;
}
#dashboard_navigation ul li:hover {
	background:#E5F6FF;
}

#dashboard_navigation ul li a {
    font-weight:bold;
    text-decoration:none;
}


/* ***************************************
	ANNOUNCEMENT
**************************************** */
#announcement {
   margin: 5px auto;
   padding: 7px;
   background: #ceffcc;
   border: 2px solid #18ba18;
   border-radius: 5px;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
}
#announcement h2 {
  color: #000;
  font-size:14px;
  font-weight: bold;
}

/* ***************************************
	GROUPS ELEMENT
**************************************** */
.is-groups-element {
	margin-right: 3px;
	-webkit-border-radius: 5px; 
	-moz-border-radius: 5px;
	borer-radius: 5px;
	padding:3px;
    border:2px solid #dedede;	
}
.is-groups-element h2 {
   color: #333;
   font-weight: bold;
   font-size: 14px;
}
.is-groups-element li.elgg-menu-item-membership, .is-groups-element li.elgg-menu-item-feature{
  display: none;
}

/* ***************************************
	RIVER RESPONSE
**************************************** */
.elgg-river-responses {
padding-bottom: 20px;
}

/* ***************************************
	SUPPORT TEXT
**************************************** */
a.ishouvik {
	margin: 10px;
	height:50px;
	width: 200px;
	color: #333;
	font-weight: bold;
	font-size: 14px;
	background: white url(<?php echo $vars['url']; ?>mod/ishouvik_riverdashboard/graphics/ishouvik.jpg) no-repeat center;
	border: 3px solid #dedede;
	display:block;
}
a.ishouvik:hover {
	color: #333;
	text-decoration: none;
	border: 3px solid #333;
}
