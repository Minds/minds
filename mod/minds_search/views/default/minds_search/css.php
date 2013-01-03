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
.minds-search-section{
	clear:both;
	display:block;
	margin:10px 0;
}
.minds-search-item{
	height:200px;
	width:150px;
	float:left;
	margin:10px;
	padding:5px;
	display:block;
	background:#EFEFEF;
	text-align:center;
	
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
}
.minds-search-item:hover{
	background:#4690D6;
}
.minds-search-item img{	
	-webkit-border-radius:5px;
	-moz-border-radius:5px;
	border-radius:5px;
	max-height:150px;
	max-width:150px;
}
.minds-search-item h3{
	font-size:11px;
	font-weight:normal;
}
a .minds-search-item p{
	font-size:11px;
	font-weight:normal;
	color:#999;
}
a:hover .minds-search-item p{
	color:#FFF;
}
.minds-search-section-video .minds-search-item{
	height:125px;
}
.minds-search-section-sound .minds-search-item{
	height:125px;
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
	margin:5px;
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
