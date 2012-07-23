<?php
/**
 * CSS Extensions for Minds Theme
 */
?>

/***************
 *** TOP BAR ***
 **************/
/* Top Menu
 */
.elgg-page-topbar > .elgg-inner{
	
 }
.elgg-menu.elgg-menu-site.elgg-menu-site-default{
	position:relative;
	float:left;
    padding-top:2px;
   	
}
/* More Drop Down
 */
.elgg-menu.elgg-menu-site.elgg-menu-site-more{
	position:absolute;
}

/*Search modifications 
 */
li.elgg-menu-item-search > a, li.elgg-menu-item-login > a  {
	padding:0;
}

/* Login button 
 */
#login-dropdown{
	position:relative;
    top:0;
}
.elgg-button.elgg-button-dropdown{
	font-size:12px;
	width:65px;
    border:0;
}
.elgg-button.elgg-button-dropdown:hover{
	background:#FFF;
    color:#333;
}
/***************
 **CUSTOMINDEX**
 **************/
.minds_index > .logo{
	margin:auto;
    width:200px;
} 
.minds_index > .search{
	width:600px;
	margin:auto;
}
.minds_index > .search input{
	float:left;
    width:500px;
}
.minds_index > .search button{
	padding:8px;
    float:left;

}
.minds_index > object{
	margin-top:10px;
	background:#000;
}
