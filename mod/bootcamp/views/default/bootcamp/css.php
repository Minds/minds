/**
 * Bootcamp
 */
.bootcamp{}
.bootcamp h2{
	
}
.bootcamp .progress{
	float:right;
	text-align:right;
}
.bootcamp .progress h3{
	font-size:64px;
	font-weight:bold;
	color:#4690D6;
}
.bootcamp .progress p{
	margin-top:25px;
}


.bootcamp .step{
	display:block;
	position:relative;
	clear:both;
	margin:25px 0;
	padding:25px 0;
	border-bottom:1px solid #EEE;
}
.bootcamp a .step{
	color:#333;
}
.bootcamp a:hover .step{
	text-decoration:none;
	background:#EEE;
}

.bootcamp .step > .number{
	font-family: 'Ubuntu Light', 'Ubuntu', 'Ubuntu Beta', UbuntuBeta, Ubuntu, 'Bitstream Vera Sans', 'DejaVu Sans', Tahoma, sans-serif;
	font-size:72px;
	font-weight:bold;
	float:left;
	color:#999;
	padding:10px;
	display:inline-block;
	width:75px;
}
.bootcamp a:hover .step > .number{
	color:#4690D6;
}
.bootcamp .step.completed > .number{
	color:#2C6700;
}

/**
 * Sidebar specifics
 */
.bootcamp.sidebar .step{
	padding:10px;
}
.bootcamp.sidebar .step > .number{
	height:45px;
	padding:0;
	width:100%;
	font-size:64px;
	text-align:center;
}
