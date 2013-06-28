<?php
	/**
	 * Elgg translation browser.
	 * 
	 * @package translationbrowser
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Mariusz Bulkowski
	 * @author v2 Pedro Prez
	 * @copyright 2009
	 * @link http://www.pedroprez.com.ar/
	 */
?>


#language-selected span{font-weight:bolder;}

#cont-countries {}
#cont-countries br {display:none;}
#cont-countries input.submit_button{display:none; position:absolute; margin:5px 0 0 600px;}
#cont-countries label{cursor:pointer; font-size:1.0em; padding:0px; display:block; border:1px solid green; margin:2px 0px; background:#F3F3F3; border:1px solid #CFCFCF;}
#cont-countries label:hover{background:#DFEEFF;}
#cont-countries label.selected{background:#CFE6FF; -moz-border-radius:4px;}
#cont-countries label span{padding:8px 4px; display:block;}
#cont-countries label input{display:none;}

/*IE6*/
* html #cont-countries input.submit_button{padding:2px 4px; margin-left: 590px;}

/*IE7*/
*:first-child+html #cont-countries input.submit_button{padding:2px 4px; margin-left: 590px;}

#cont-words {}
#cont-words textarea {width:97%; height:auto; margin-left:4px; font-size:1.0em; margin-bottom:18px;}
#cont-words img {margin:0px 5px 0px 2px;}
#cont-words p {margin:0px; padding:2px; font-size:1.0em;}
#cont-words div.lang_trans {width:98%; border:1px solid #CCCCCC; padding:4px 2px; margin-left:4px; background:#EFF7FF; font-size:1.0em;}

#browsertranslate div.cont-button {text-align:right; padding-right:5px;}
#browsertranslate input{border:none;}
#browsertranslate label{font-size:1.0em; padding: 2px 0px 2px 3px;}
#cont-words div.selecttype {width:97%; border:1px solid #CCCCCC; padding:4px 2px; margin-left:4px; font-size:1.0em; background:#DFDFDF; }
#cont-words p.highlighter {padding:20px 0px 2px 0px;}

#language-selected {border: 1px solid rgb(153, 153, 153); margin: 6px 0px; padding: 6px; background:#DFDFDF;}
#select-module {margin-top:4px 0px; padding:2px;}    