<?php
/**
 * Tidypics CSS
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */
?>

/* ***************************************
	TIDYPICS
*************************************** */
.elgg-module-tidypics-album,
.elgg-module-tidypics-image {
	width: 161px;
	text-align: center;
	margin: 5px 0;
}
.elgg-module-tidypics-image {
	margin: 5px auto;
}

.tidypics-gallery-widget > li {
	width: 100%;
}
.tidypics-photo-wrapper {
	position: relative;
}

.tidypics-heading {
	color: #0054A7;
}
.tidypics-heading:hover {
	color: #0054A7;
	text-decoration: none;
}

.tidypics-input-thin {
	width: 120px;
}

#tidypics-sort li {
	width:153px;
	height:153px;
}

.tidypics-river-list > li {
	display: inline-block;
}

.tidypics-photo-item + .tidypics-photo-item {
	margin-left: 7px;
}

.tidypics-gallery > li {
	padding: 0 10px;
}

.tidypics-album-nav {
	margin: 3px 0;
	text-align: center;
	color: #aaa;
}

.tidypics-album-nav > li {
	padding: 0 3px;
}

.tidypics-album-nav > li {
	vertical-align: top;
}

/* ***************************************
	Tagging
*************************************** */
.tidypics-tagging-border1 {
	border: solid 2px white;
}

.tidypics-tagging-border1, .tidypics-tagging-border2,
.tidypics-tagging-border3, .tidypics-tagging-border4 {
    filter: alpha(opacity=50);
	opacity: 0.5;
}

.tidypics-tagging-handle {
    background-color: #fff;
    border: solid 1px #000;
    filter: alpha(opacity=50);
    opacity: 0.5;
}

.tidypics-tagging-outer {
    background-color: #000;
    filter: alpha(opacity=50);
    opacity: 0.5;
}

.tidypics-tagging-help {
	position: absolute;
	left: 50%;
	top: -25px;
	width: 250px;
	margin-left: -125px;
	text-align: center;
}

.tidypics-tagging-select {
	position: absolute;
	max-width: 300px;
}

.tidypics-tag-wrapper {
	display: none;
	position: absolute;
}

.tidypics-tag {
	border: 2px solid white;
	clear: both;
}

.tidypics-tag-label {
	float: left;
	margin-top: 5px;
	color: #666;
}

/* ***************************************
	Tagging
*************************************** */
#tidypics_uploader {
	position:relative;
	width:400px;
	min-height:20px;
}

#tidypics_choose_button {
	position:absolute;
	top:0;
	left:0;
	z-index:0;
	display:block;
	float:left;
}

#tidypics_flash_uploader {
	position:relative;
	z-index:100;
}

/* ***************************************
	AJAX UPLOADER
*************************************** */
#tidypics-uploader-steps {
	list-style: none;
}

#tidypics-uploader-steps li a {
	font-weight:bold;
}

.tidypics-choose-button-hover {
	color:#0054a7;
	text-decoration:underline;
}

.tidypics-disable {
	color:#cccccc;
}

.tidypics-disable:hover {
color:#cccccc;
text-decoration:none;
}


.uploadifyQueueItem {
background-color:#F5F5F5;
border:2px solid #E5E5E5;
font-size:11px;
margin-top:5px;
padding:10px;
width:350px;
}

.uploadifyProgress {
background-color:#FFFFFF;
border-color:#808080 #C5C5C5 #C5C5C5 #808080;
border-style:solid;
border-width:1px;
margin-top:10px;
width:100%;
}

.uploadifyProgressBar {
background-color: #0054a7;
width: 1px;
height: 3px;
}

#tidypics-uploader {
	position:relative;
	width:400px;
	min-height:20px;
}

#tidypics-choose-button {
position:absolute;
top:0;
left:0;
z-index:0;
display:block;
float:left;
}

#tidypics-flash-uploader {
position:relative;
z-index:100;
}

.uploadifyQueueItem .cancel {
	float: right;
}

.uploadifyError {
border: 2px solid #FBCBBC;
background-color: #FDE5DD;
}

<?php
return true;
?>

/* ---- tidypics object views ---- */

.tidypics_wrapper > table.entity_gallery {
	border-spacing: 0;
}

.tidypics_wrapper .entity_gallery td {
	padding: 0;
}

.tidypics_wrapper .entity_gallery_item,
.tidypics_wrapper .entity_gallery_item:hover {
	background-color: transparent;
	color: inherit;
}

#tidypics_breadcrumbs {
margin:5px 0 15px 0;
font-size:80%;
}

#tidypics_desc {
padding:0 20px;
font-style:italic;
}

#tidypics_image_nav {
text-align:center;
}

#tidypics_image_wrapper {
margin:10px 0 10px 0;
text-align:center;
}

#tidypics_image {
border:1px solid #dedede;
padding:5px;
}

#tidypics_image_nav ul li {
display:inline;
margin-right:15px;
}

#tidypics_controls {
text-align:center;
margin-bottom:10px;
}

#tidypics_controls a {
margin:10px;
}

#tidypics_controls ul {
list-style:none; 
margin:0px; 
padding:8px;
}

#tidypics_controls ul li {
padding:2px 10px 2px 22px;
margin:2px 0px; 
display:inline;
}

.tidypics_info {
padding:20px;
}

#tidypics_exif {
padding-left:20px;
font-size:80%;
}

.tidypics_album_images {
float:left;
width:153px; 
height:153px;
margin:3px;
padding:4px;
border:1px solid #dedede;
text-align:center;
}

.tidypics_album_cover {
padding:2px;
border:1px solid #dedede;
margin:5px 0;
}

.tidypics_album_widget_single_item {
margin-bottom:8px;
}

.tidypics_album_gallery_item {
float:left;
margin-bottom:20px;
padding: 4px;
text-align:center;
width: 160px;
}

.tidypics_line_break {
width: 100%;
clear: both;
}

.tidypics_gallery_title {
font-weight:bold;
}

.tidypics_popup {
border:1px solid #3B5999; 
width:200px; 
position:absolute;
z-index:10000; 
display:none; 
background:#ffffff; 
padding:10px; 
font-size:12px; 
text-align:left;
}

/* ------ tidypics widget view ------  */

#tidypics_album_widget_container {
text-align:center;
}

.tidypics_album_widget_timestamp {
color:#333333;
}

.tidypics_widget_latest {
margin: 0 auto;
width: 208px;
}

/* ---------  image upload/edit forms  ------------   */

#tidpics_image_upload_list li {
margin:3px 0;
}

.tidypics_edit_image_container {
padding:5px;
margin:5px 0;
overflow:auto;
}

.tidypics_edit_images {
float:right;
width:160px; 
height:160px;
margin:4px;
padding:5px;
border:1px solid #dedede;
text-align:center;
}

.tidypics_image_info {
float:left;
width:60%;
}

.tidypics_image_info label {
font-size:1em;
}

.tidypics_caption_input {
	width:98%;
	height:100px;
}

/* ---- tidypics group css ----- */

#tidypics_group_profile {
-webkit-border-radius: 8px; 
-moz-border-radius: 8px;
background:white none repeat scroll 0 0;
margin:0 0 20px;
padding:0 0 5px;
}


/* ---------  tidypics river items ------------   */

.river_object_image_create {
	background: url(<?php echo $vars['url']; ?>mod/tidypics/graphics/icons/river_icon_image.gif) no-repeat left -1px;
}
.river_object_album_create {
	background: url(<?php echo $vars['url']; ?>mod/tidypics/graphics/icons/river_icon_album.gif) no-repeat left -1px;
}
.river_object_image_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.river_object_album_comment {
	background: url(<?php echo $vars['url']; ?>_graphics/river_icons/river_icon_comment.gif) no-repeat left -1px;
}
.river_user_tag {
	background: url(<?php echo $vars['url']; ?>mod/tidypics/graphics/icons/river_icon_tag.gif) no-repeat left -1px;
}

/* ----------- tagging ---------------- */
#tidypics_tag_instructions {
background:#BBDAF7; 
border:1px solid #4690D6;  
padding:10px;
height:25px;
min-width:360px;
display:none;
overflow:hidden; 
position:absolute; 
z-index:10000;
-webkit-border-radius: 8px; 
-moz-border-radius: 8px;
}

#tidypics_tag_instruct_text {
padding-top: 3px;
float: left;
}

#tidypics_tag_instruct_button_div {
float: left;
margin-left: 15px;
}

#tidypics_tag_instruct_button {
margin:0;
}

#tidypics_tag_menu {
width:240px;
max-height:400px;
overflow:hidden;
-webkit-border-radius: 8px; 
-moz-border-radius: 8px;
}

.tidypics_popup_header {
width:100%;
margin-bottom:10px;
}


#tidypics_tagmenu_left {
width:175px;
float:left;
}

#tidypics_tagmenu_right {
float:left;
}

#tidypics_tagmenu_left .input-filter {
width:150px;
}

#tidypics_tagmenu_right .submit_button {
margin-top:2px;
}

#tidypics_delete_tag_menu {
-webkit-border-radius: 8px; 
-moz-border-radius: 8px;
overflow:hidden;
}

.tidypics_tag {
display:none;
background:url(<?php echo $vars['url']; ?>mod/tidypics/graphics/spacer.gif); 
border:2px solid #ffffff; 
overflow:hidden; 
position:absolute; 
z-index:0;
}

.tidypics_tag_text {
display:none;
overflow:hidden; 
position:absolute; 
z-index:0;
text-align:center;
background:#BBDAF7;
border:1px solid #3B5999;
-webkit-border-radius:3px; 
-moz-border-radius:3px;
padding:1px;
}

#tidypics_phototags_list {
padding:0 20px 0 20px;
}

#tidypics_phototags_list ul {
list-style:none; 
margin:0px; 
padding:8px;
}

#tidypics_phototags_list ul li {
padding-right:10px;
margin:2px 0px; 
display:inline;
} 

#tidypics_image_upload_list {
list-style: none;
}

#tidypics_album_sort {
padding:0;
margin:0;
}

#tidypics_album_sort li {
float:left;
margin:3px;
width:161px;
height:161px;
list-style:none;
}

#tidypics_album_sort img {
border:1px solid #dedede;
padding:4px;
}

