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

/* ***************************************
	MISC
*************************************** */
#login-dropdown {
	position: absolute;
	top:-25px;
	right:0;
	z-index: 10000;
        //border: 1px solid white;

}
.standard-title{
    color: #73BAEF;
}

/* ***************************************
	DROPDOWN CATEGORY
*************************************** */

.elgg-input-dropdown{
        border: 3px solid #CCC;
        background: #FFF;
        border-radius: 7px;
        font-size: 1.0em;
        color: #83CAFF;
        font-weight: bold;
}
#county-drop select{
        border: 3px solid #CCC;
        background: #FFF;
        border-radius: 7px;
        font-size: 1.0em;
}

#county-drop .elgg-input-dropdown{
        color: #83CAFF;
        font-weight: bold;
        width: 200px;
}
.elgg-input-dropdown option {
        color: #222;
        font-size: 1.0em;
}


#county-drop select option{
        font-size: 1.0em;
}

/* ***************************************
	AVATAR UPLOADING & CROPPING
*************************************** */

#current-user-avatar {
	border-right:1px solid #ccc;
}
#avatar-croppingtool {
	border-top: 1px solid #ccc;
}
#user-avatar {
	float: left;
}
#user-avatar-preview {
	float: left;
	position: relative;
	overflow: hidden;
	width: 100px;
	height: 100px;
}

/* ***************************************
	FRIENDS COLLECTIONS
*************************************** */

#friends_collections_accordian li {
	color: #666;
}
#friends_collections_accordian li h2 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	background: none repeat scroll 0 0 #4690D6;
	color: white;
	cursor: pointer;
	font-size: 1.2em;
	margin: 10px 0;
	padding: 4px 2px 4px 6px;
}
#friends_collections_accordian li h2:hover {
	background-color: #333;
}
#friends_collections_accordian .friends_collections_controls {
	float: right;
	font-size: 70%;
}
#friends_collections_accordian .friends-picker-main-wrapper {
	background: none repeat scroll 0 0 white;
	display: none;
	padding: 0;
}


/* ***************************** SHORTCUTS ********************* */

.orange{
	background: #FF8700;
}

.green{
        background: #76E792;
}

.yellow{
	background: #83CAFF;
}

.rb-container{
}

#button-wrapper-no{
        width: 125px;
}
#button-wrapper-no2{
        width: 125px;
}
#button-wrapper-no3{
        width: 125px;
}

#button-wrapper-no:active{

}

.rb-button{
	width: 125px;
	height: 24px;
	text-align: center;
	overflow: hidden;
        margin-top: 1px;
	//border-top:1px solid #FFF;
	cursor: pointer;
}

.rb-button h2 {
	padding: 0;
	margin: 0;
	font-size: 0.9em;
	padding: 6px;
	color: #FFF;
	font-family: Trebuchet MS1, Trebuchet MS, sans-serif;
}
#button-jobs{
	display: none;
}
#button-services{
	display: none;
}
#button-my_acc{
	display: none;
}

#button-wrapper {
	height: auto;
}

#button-wrapper:hover #button-jobs{
	display: block;
}
#button-wrapper:hover #button-services{
	display: block;
}
#button-wrapper:hover #button-my_acc{
	display: block;
}

.rb-wrapper{
	position: absolute;
	left: 700px;
        bottom: -20px;
}
.more-right{
	position: absolute;
	left: 830px;

}
.more-more-right{
	position: absolute;
	left: 770px;

}

.rb-hanger{
	width: 125px;
	height: 10px;
	border-radius: 10px 10px 0 0;
	cursor: pointer;
	text-align: center;
}
.rb-hanger h2 {
	padding: 1px;
	margin: 0;
	font-size: 0.7em;
	color: #FFDC88;
	font-family: Trebuchet MS1, Trebuchet MS, sans-serif;
	text-align: center;
}

buttons h2 {
	
}



#message-notification{
    color:#CEFF16; //FF5080;
    font-weight: bold;
    font-size: 1.3em;
}

#topmenu-username{
float:right;
}

/* **************************************************
                    picture in register box
***************************************************** */

#register-picture{
    background-color: trasparent; //rgba(200,0,0,0.5);
    background-image: url(<?php echo elgg_get_site_url(); ?>mod/pab_theme/graphics/woman350.png);
    background-repeat: no-repeat;
    background-position: bottom right;
    
    width: 600px;
    float: right;
    height: 350px;
}


/* **************************************************
                    category select box
***************************************************** */

.category-select{
    padding: 2px;
    width: 330px;
    float: left;
    margin: 2px;
    border-radius: 5px;
    border: 3px solid transparent;
}

.category-select:hover{
    //box-shadow: inset 0 0 5px 5px rgba(0,0,0,0.2);
    border: 3px solid #DDD;
}

.category-select span{
     width: auto;
     
}

.category-select input{
    vertical-align: bottom;
    position: relative;
}
