/*-------------------------------
EMBED VIDEO
-------------------------------*/

.videoembed_video {
  padding: 0; 
  margin:10px 0;
  overflow: hidden;
  align: center;
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
	color:#FFF;
	padding:15px 0 0 55px;
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