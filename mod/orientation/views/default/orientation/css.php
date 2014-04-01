/**
 * Bootcamp renamed orientation
 */
.orientation{}
.orientation h2{

}

.orientation .title{
	position: relative;
}
.orientation .blurb{
	visibility: hidden;
	font-size: 20px;
	padding: 100px 10px;
}

.orientation .progress{
 	//visibility: hidden;
 	float:right;
 	text-align:right;
 	font-size:18px;
 	postion: absolute;
 	padding-right: 10%;
 	margin: -6%;
}	
.orientation .progress h3{
	//visibility: hidden;
 	font-size:80px;
 	font-weight:bold;
  	color:#4690D6;
 	margin-top:16px;
}
.orientation .progress p{
	//visibility: hidden;
	margin-top:20px;
	font-weight:bold;
	padding-right: 10px;
	padding-top: 10px
}

.orientation .step{
	display: block;
	position:relative;
	margin:0px 0px;
	padding:14% 20% 0% 25%;
}
.orientation a .step{
	color:#333;
}
.orientation .entypo{
	font-size: 125px;
	text-align: center;
	text-decoration:none;
}
.orientation h3{
	padding-top:24px;
}

/**
 * Sidebar Specifc
 */
.orientation.sidebar{
	position:relative;
	width:100%;
	height:auto;
	clear:both;
	padding:0 8px;
}
.orientation.sidebar .step {
	padding:0;
}
.orientation.sidebar .entypo{
		font-size:48px;
}
.orientation.sidebar h3{
	color:#333;
}

.orientation a:hover, .orientation a:hover .step{
	text-decoration: none;
	color: #4690D6;
}

.orientation a:hover .elgg-item{
	text-decoration: none;
	color: #4690D6;
}

.orientation .inner{
	text-align: center;
	font-size: 18px;
	margin: 0px 0px;
	padding: 30px 0px 0px 0px;
	line-height: 100%;
}
.orientation .content{
 	visibility: hidden;
}

.orientation .step > .tick{
	position: absolute;
	right: 16px;
	top: 16px;
	color: green;
	font-size: 42px;
}

.orientation .step > .number{
	visibility: hidden;
	font-family: 'Ubuntu Light', 'Ubuntu', 'Ubuntu Beta', UbuntuBeta, Ubuntu, 'Bitstream Vera Sans', 'DejaVu Sans', Tahoma, sans-serif;
	font-size:72px;
	font-weight:bold;
	float:left;
	color:#999;
	padding:10px;
	display:inline-block;
	width:75px;
}
.orientation a:hover .step > .number{
	text-decoration:none;
}
.orientation .step.completed > .number{
	color:#2C6700;
}
.orientation .step.completed{
}

/**
 * Register pages
 */
.orientation-register-wrapper{
	width: 800px;
	margin: auto;
	height: auto;
	border: 1px solid #EEE;
	border-radius: 3px;
	padding: 16px;
	background: #FFF;
	position: relative;
	display:table;
}
.orientation-register-wrapper > .orientation-menu{
	display:table-cell;
	width: 100px;
	border-right: 1px solid #EEE;
	position: relative;
}
.orientation-register-wrapper > .orientation-menu > ul{
}
.orientation-register-wrapper > .orientation-menu > ul > li{
	text-align:right;
	padding: 8px 25px 8px 0;
}
.orientation-register-wrapper > .orientation-menu > ul > li.active a{
	color:#888;
}
.orientation-register-wrapper > .orientation-menu > ul > li > a{
	font-weight:bold;
}
.orientation-register-wrapper > .orientation-content{
	display:table-cell;
	width: 680px;
	height: 100%;
	position: relative;
	padding: 16px;
}
.orientation-register-wrapper > .orientation-content .orientation-action-buttons-wrapper{
	float:right;
}

.orientation-block{
	margin:16px 8px;
}
.orientation-block label{
	/*font-weight:normal;*/
}
.orientation-block.channel-info span{
	width:100%;
	display:block;
	margin:16px 0;
}
.orientation-block.channel-info input{
	width:150px;
	margin-right:12px;
}
.orientation-block.channel-info select{
	-webkit-appearance: none;
	padding: 8px;
	border: 1px solid #DDD;
	background: #FAFAFA;
	margin-right:12px;
}

.orientation-content #user-avatar-cropper{
	width:400px;
}
.orientation-content .elgg-foot .elgg-button-submit{
	display:none;
}
