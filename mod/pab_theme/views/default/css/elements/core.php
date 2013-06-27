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

/* Clearfix */
.clearfix:after,
.elgg-grid:after,
.elgg-layout:after,
.elgg-inner:after,
.elgg-page-header:after,
.elgg-page-footer:after,
.elgg-head:after,
.elgg-foot:after,
.elgg-col:after,
.elgg-image-block:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;	
}

/* Fluid width container that does not wrap floats */
.elgg-body,
.elgg-col-last {
	display: block;
	width: auto;
	word-wrap: break-word;
	overflow: hidden;
	
	/* IE 6, 7 */
	zoom:1;
	*overflow:visible;
}

<?php //@todo isn't this only needed if we use display:table-cell? ?>
.elgg-body:after,
.elgg-col-last:after {
	display: block;
	visibility: hidden;
	height: 0 !important;
	line-height: 0;
	
	 Stretch to fill up available space 
	font-size: xx-large;
	content: " x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x x ";
}

/* ***************************************
 * MENUS
 *
 * To add separators to a menu:
 * .elgg-menu-$menu > li:after {content: '|'; background: ...;}
 *************************************** */
/* Enabled nesting of dropdown/flyout menus */
.elgg-menu > li { position: relative; }
.top-menu > li { position: relative; }

/* Separators should only come between list items */
.elgg-menu > li:last-child:after { display: none } 
.top-menu > li:last-child:after { display: none } 

/* Maximize click target */
.elgg-menu > li > a { display: block }
.top-menu > li > a { display: block }

/* Horizontal menus w/ separator support */
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a,
.elgg-menu-hz > li > span {
	vertical-align: middle;
}

#myaccount-messages{
color:#CEFF16;
float:right;
}

/* Allow inline image blocks in horizontal menus */
.elgg-menu-hz .elgg-body:after { content: '.'; }

<?php //@todo This isn't going to work as-is.  Needs testing ?>
/* Inline block */
.elgg-gallery > li,
.elgg-button,
.elgg-icon,
.elgg-menu-hz > li,
.elgg-menu-hz > li:after,
.elgg-menu-hz > li > a,
.elgg-menu-hz > li > span {
	/* Google says do this, but why? */
	position: relative;

	display: inline-block;
	
	/* Inline-block: IE 6, 7 */
	zoom: 1;
	*display: inline;
}
