<?php
/**
 * Elgg Search css
 * 
 */
?>
/**
 * Minds Search
 */
.minds-search{}
form.minds-search{
	float:left;
	width:50%;
}
.minds-search input[type=text]{
	margin:10px;
}
.minds-search .submit{
	display:none;
}
.minds-search-section{
	clear:both;
	display:block;
	margin:10px 0;
}
.minds-search-item{
	height:225px;
	width:160px;
	float:left;
	margin:5px;
	padding:5px;
	display:block;
	background:#FFF;
	border: 1px solid #CCC;
	overflow:hidden;
}
.minds-search-item:hover{
	background:#F3F3F3;
}
.minds-search-item img{	
	width:100%;
}
.minds-search img.full{
	width:100%;
}
.minds-search-item h3{
	font-size:12px;
	font-weight:bold;
}
a .minds-search-item p{
	font-size:11px;
	font-weight:normal;
	color:#999;
}
.minds-search-section-video .minds-search-item{
	/*height:125px;*/
}
.minds-search-section-sound .minds-search-item{
	/*height:125px;*/
}
.minds-search-section-sound .minds-search-item img{
	height:75px;
	background:#4690D6;
}
.minds-search .elgg-pagination{
	clear:both;
	margin:25px;
}
.minds-search.minds-search-index-page{
	width:75%;
	margin:auto;
}
.minds-search.minds-search-index-page .search-submit-button{
	width:100px;
	float:right;
}
.tidypics-album-block .tidypics-photo-item{
	width:63px;
	margin:2px;
	float:left;
	padding:3px;
	background:#FFF;
	border:1px solid #CCC;
}
.tidypics-album-block {
	float:left;
	clear:both;
	width:100%;
}
.minds-search-item-video span{
	position: absolute;
	display: inline-block;
	cursor: pointer;
	margin: auto;
	height: 100px;
	width: 160px;
	background: transparent url(<?php echo elgg_get_site_url();?>mod/embed_extender/graphics/play_button.png) no-repeat center center;
	z-index: 2;
}
.minds-search .ad{
	padding:25px 15px;
}

.minds-search-nav{
	width:90%;
	height:35px;
	clear:both;
	padding:10px;
	margin:auto;
}
.minds-search-nav-section{
	float:left;
	padding-right:25px;
}
.minds-search-nav-section li{
	float:left;
	padding-right:10px;
}
.minds-search-nav-dropdown a{
	color:#333;
}
.minds-search-nav-dropdown ul{
	display:none;
}
.minds-search-nav-dropdown ul a li{
	float:none;
}
.minds-search-menu-licenses{
	display:none;
}
.minds-search-nav-dropdown:hover ul{
	position:absolute;
	display:block;
	width:175px;
	z-index:1;
	background-color: rgba(0, 0, 0, 0.75);
	margin-top:15px;
	border:1px solid #333;
	padding:5px;
	border-radius: 4px 4px 4px 4px;
	-webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	-moz-box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
	box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.25);
}
.minds-search-nav-dropdown ul a{
	color:#FFF;
}
.minds-search-nav-dropdown a:hover{
	color:#4690D6;
}

/************************
 * Individual page
 */
.elgg-menu-search-result{
	float:right;
}
.elgg-menu-search-result > li{
	float:left;
	margin:0 5px;
}
.elgg-menu-search-result > .elgg-menu-item-remind{
	margin:5px 0 5px 10px;
}
.search-result-license{
	float:left;
	margin-right:25px;
}
.search-result-social > .minds-social{
	margin-top:15px;
}
/**********************************
Search plugin
***********************************/
.elgg-search-header {
	margin-top:-1px;
	height: 23px;
}
.elgg-search input[type=text] {
	/*width: 230px;*/
    width:275px;
}
.elgg-menu-item-search.main input[type=text]{
	width:350px;
}
.elgg-search input[type=submit] {
	display: none;
}
.elgg-search input[type=text] {	
	border: 1px solid #CCC;
	color: #CCC;
	font-size: 12px;
	padding: 3px 4px 3px 26px;
	background: #F2F2F2 url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png) no-repeat 4px -916px;
    
    -webkit-border-radius:2px;
	-moz-border-radius:2px;
	border-radius:2px;
	
	-webkit-box-shadow: 0 0 0;
	-moz-box-shadow: 0 0 0;
	box-shadow: 0 0 0;
}
.elgg-search input[type=text]:focus, .elgg-search input[type=text]:active {
	background-color: white;
	/*background-position: 2px -917px;*/
	color: #000;
}

.search-list li {
	padding: 5px 0 0;
}
.search-heading-category {
	margin-top: 20px;
	color: #666666;
}

.search-highlight {
	background-color: #bbdaf7;
}
.search-highlight-color1 {
	background-color: #bbdaf7;
}
.search-highlight-color2 {
	background-color: #A0FFFF;
}
.search-highlight-color3 {
	background-color: #FDFFC3;
}
.search-highlight-color4 {
	background-color: #ccc;
}
.search-highlight-color5 {
	background-color: #4690d6;
}

.ui-autocomplete{
	width:200px;
}
.elasticsearch-round-corners{
	-webkit-border-radius:6px;
	-moz-border-radius:6px;
	border-radius:6px;
}
