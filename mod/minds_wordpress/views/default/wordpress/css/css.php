
@font-face {
    font-family: 'entypo';
    src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.eot?') format('eot'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.woff') format('woff'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.ttf') format('truetype'),
         url('<?php echo elgg_get_site_url();?>mod/minds/vendors/entypo/entypo.svg') format('svg');
    font-weight: normal;
    font-style: normal;
}

@font-face {
  font-family: 'fontello';
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?96059246');
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?96059246#iefix') format('embedded-opentype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.woff?96059246') format('woff'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.ttf?96059246') format('truetype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.svg?96059246#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}

@font-face {
  font-family: 'Ubuntu';
  font-style: normal;
  font-weight: 300;
  src: local('Ubuntu Light'), local('Ubuntu-Light'), url(https://themes.googleusercontent.com/static/fonts/ubuntu/v4/_aijTyevf54tkVDLy-dlnLO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
}

#Minds-Toolbar {
    font-family: 'Ubuntu Light','Ubuntu','Ubuntu Beta',UbuntuBeta,Ubuntu,'Bitstream Vera Sans','DejaVu Sans',Tahoma,sans-serif;
}

#Minds-Toolbar h1, #Minds-Toolbar h2, #Minds-Toolbar h3 {
font-family: 'Ubuntu Light','Ubuntu','Ubuntu Beta',UbuntuBeta,Ubuntu,'Bitstream Vera Sans','DejaVu Sans',Tahoma,sans-serif;
}

#Minds-Toolbar .topbar .right .elgg-form{
	margin-top:20px;
}
#Minds-Toolbar .topbar .right .elgg-form input[type=text], .topbar .right .elgg-form input[type=password]{
	float:left;
	width:104px;
	height:28px;
	margin-right:8px;
	font-size:11px;
	padding:0 8px;
}
#Minds-Toolbar .topbar .right .elgg-form .elgg-button-submit{
	padding: 4px;
	min-width: 0;
	background:#4690D6;
	border:1px solid #4690D6;
}
#Minds-Toolbar .topbar .right .elgg-menu.mtm{
	margin:0;
	font-size:11px;
	width:100%;
	clear:both;
}

#Minds-Toolbar .topbar .right .elgg-menu.mtm li{
	border:0;
	padding:0 16px 0 2px;
}
#Minds-Toolbar .topbar .right.elgg-menu.mtm li a{
	border:0;
}

#Minds-Toolbar .topbar .right .social-login{

	display:none;
	position: absolute;
	background: #EEE;
	width: auto;
	border-radius: 3px;
	top: 89px;
	right: 0;
}
#Minds-Toolbar .topbar .right .social-login > .social_login{
	margin:5px;
}

#Minds-Toolbar .elgg-menu-general.login-box li{
	float:right;	
}




/* Top Menu
 */
.entypo{
	font-family:'fontello', 'Ubuntu', Tahoma, sans-serif;
	font-size:18px;
	font-weight:normal;
	text-decoration:none;
}
.elgg-menu .entypo.elements{
	font-size:2px;
}
.elgg-menu .elgg-menu-item-logout .entypo{
	padding-top:2px;
	font-size:20px;
}
.elgg-menu-item-register  a{
	color:#4690D6 !important;
	padding:0 10px;
}
.index .elgg-main{
	overflow: visible !important;
	border:0;
	box-shadow:none !important;
	-webkit-box-shadow:none !important;
	-moz-box-shadow:none !important;
}
.login{
	width:300px !important;
	margin:auto;
}
.login .social_login{
	margin:10px 0;
}
.login .elgg-button-submit {
	float:right;
}
.login li{
	width:100%;
}
.elgg-menu-river li{
	padding:0 3px;
}
.elgg-menu-item-delete a:hover{
	color:red;
}

.elgg-menu-comments li{
	padding:0 3px;
}
.elgg-menu-comments li a{
	color:#CCC;
}





.hero, .elgg-page-default {
	min-width: 998px;
	height:auto;
	min-height:100%;
}
.hero .header > .inner, .elgg-page-default .elgg-page-header > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	height: 90px;
}
.hero > .body > .inner, .elgg-page-default .elgg-page-body > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	padding: 16px 0;
}
.elgg-page-default .elgg-page-footer > .elgg-inner {
	width: 90%;
	margin: 0 auto;
	padding: 5px 0;
}

.hero > .topbar > .inner > .center {
    width: 50%;
}

.hero > .topbar > .inner > div {
    float: left;
    position: relative;
}

.center {
    text-align: center;
}



.hero > .topbar {
	background: #F8F8F8;
	 background: rgba(255,255,255, 0.9);
	position: fixed;
    top:0;
    min-width:998px;
    width:100%;
	height: auto;
	z-index: 9000;
	box-shadow: 0 0 5px #DDD;
	-moz-box-shadow: 0 0 5px #DDD;
	-webkit-box-shadow: 0 0 5px #DDD;
}
.hero > .topbar > .inner{
	padding: 8px;
    margin:auto;
    width:90%;
}
.hero > .topbar > div{
	float:left;
	position:relative;
}
.hero > .topbar >  .left{
	width:75%;
}

.hero > .topbar >  .right{
	width:25%;
}
.hero > .topbar > .inner .global-menu{
	margin-bottom: 8px;
	float:left;
}
.hero > .topbar .logo{
	float:left;
	padding: 8px 8px;
	display:block;
	position:relative;
	width:auto;
	height:50px;
}
.hero > .topbar .logo > img{
	height:100%;
}
.hero > .topbar .search{
	margin:16px;
	float:left;
	width:50%;
}
.hero > .topbar .search input{
	margin:0;
}
.hero > .topbar .owner_block{
	margin-top:15px;
	float:right;
}
.hero > .topbar .owner_block > a > img{
	padding:8px;
}
.hero > .topbar .owner_block > a > .text{
	padding:8px;
	float:left;
	text-align:right;
	text-decoration:none;
}
.hero > .topbar .actions{
	margin-top:35px;
	float:right;
}
.hero > .topbar .more{
	clear:both;
	display:none;
	float:right;
}
.hero > .topbar .right:hover .more{
	display:block;
}
.hero > .topbar .more a{
	color:#333;
	font-size:11px;
}

.hero > .topbar > .left {
    width: 25%;
}
.hero > .topbar >  div {
    float: left;
    position: relative;
}
.hero > .topbar > .right {
    width: 25%;
}
.hero > .topbar >  div {
    float: left;
    position: relative;
}


















div.minds-buttons div.minds-button {
    float: right;
    margin: 10px;
}