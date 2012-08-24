<?php
/**
 * Elgg Search css
 * 
 */
?>

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
