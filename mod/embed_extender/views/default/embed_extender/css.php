/*-------------------------------
EMBED VIDEO
-------------------------------*/

.videoembed_video {
  padding: 0; 
  margin:10px 0;
  overflow: hidden;
  align: center;
  cursor:pointer;
}
.videoembed_video img{
	z-index:1;
}
.videoembed_video span {
	position:absolute;
	display:block;
	margin:auto;
	min-width:50px;
	max-width:375px;
	height:50px;
	margin:10px;
	background: transparent url(<?php echo elgg_get_site_url(); ?>mod/embed_extender/graphics/play_button.png) no-repeat left;
	z-index:2;
}
.videoembed_video span h1{
	font-weight:bold;
	font-size:16px;
	background: rgb(0, 0, 0);/*fallback for browsers that dont support rgba*/
	background: rgba(0, 0, 0, 0.6);
	color:#FFF;
	margin:0 60px;
	padding:15px;
}
.videoembed_video span h1:hover{
	color:#4690D6;
}

.collapsable_box_content .thewire-post .videoembed_video {
  	margin: 10px 0 0 5px;
	position:relative;
    float:left; 
}
.collapsable_box_content .thewire-post .note_text{
	overflow:visible;
}