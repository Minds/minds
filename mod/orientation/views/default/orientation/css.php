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
	font-size: 20px;
	padding: 100px 10px;
}

.orientation .progress{
 	//visibility: hidden;
 	float:right;
 	text-align:right;
 	font-size:18px;
 	postion: absolute;
 	padding-right: 30px;
}	
.orientation .progress h3{
	//visibility: hidden;
 	font-size:80px;
 	font-weight:bold;
  	color:#4690D6;
 	margin-top:100px;
}
.orientation .progress p{
	//visibility: hidden;
	margin-top:20px;
	font-weight:bold;
	padding-right: 10px;
	padding-top: 10px
}
 

.orientation .elgg-list{
	
}
.orientation li{
}

.orientation .step{
	display: block;
	position:relative;
	margin:0px 0px;
	padding:80px 35px 0px 35px;
}
.orientation a .step{
	color:#333;
}
.orientation .entypo{
	font-size: 125px;
	text-align: center;
	text-decoration:none;
	padding-right: 0px;
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
	color:#4690D6;
	text-decoration:none;
}
.orientation .step.completed > .number{
	color:#2C6700;
}
.orientation .step.completed{
	color:#4690D6;
}
/**
 * Sidebar specifics
 */

.elgg-sidebar{
	color: #ff0000;
}
.orientation.sidebar .step {
	color: #ff0000;
	
}

