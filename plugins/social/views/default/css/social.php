#social-selection{
	float: left;
	/*display: block;*/
	display:none;
	/* width: 100px; */
	position: relative;
	/* height: 20px; */
	padding: 8px;
}
#social-selection label{
	padding-right:8px;
}

#social-selection input[type=checkbox]:checked ~ label {
	color:#4690D6;
}

.enable-social-share #social-selection{
	display:block;
}
