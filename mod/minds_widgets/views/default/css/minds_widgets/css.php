<?php
/**
 * CSS Extensions for Minds Theme
 */
?>
@CHARSET "UTF-8";

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
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?17546205');
  src: url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.eot?17546205#iefix') format('embedded-opentype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.woff?17546205') format('woff'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.ttf?17546205') format('truetype'),
       url('<?php echo elgg_get_site_url();?>mod/minds/vendors/fontello/font/fontello.svg?17546205#fontello') format('svg');
  font-weight: normal;
  font-style: normal;
}
.entypo{
	font-family:'fontello', 'Ubuntu', Tahoma, sans-serif;
	font-size:17px;
	font-weight:normal;
	text-decoration:none;
}
.entypo.elements{
	font-size:26px;
}

a.entypo span.mind_graf {
    display: inline !important;
}

span.mind_fall,
a.entypo span.mind_fall {
    display: none;
}
