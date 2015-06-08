<?php
/**
 * TinyMCE CSS
 *
 * Overrides on the default TinyMCE skin
 * Gives the textarea and buttons rounded corners
 * 
 * The rules are extra long in order to have enough
 * weight to override the TinyMCE rules
 */
?>
/* TinyMCE */
.mce-tinymce {
	clear:both;
}
.elgg-page .mceEditor table.mceLayout {
	border: 1px solid #CCC;
	
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.elgg-page table.mceLayout tr.mceFirst td.mceToolbar,
.elgg-page table.mceLayout tr.mceLast td.mceStatusbar {
	border-width: 0px;
}
.mceButton {
	-webkit-border-radius: 4px; 
	-moz-border-radius: 4px;
	border-radius: 4px;
}
.mceLast .mceStatusbar {
	padding-left: 5px;
}
.mce-content-body{
    padding:8px;
}
.mce-content-body img{
    padding:8px;
}
.mce-tinymce img{
     padding:8px;
}
