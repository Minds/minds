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

	$data = $vars['data'];
	$session_translate = $vars['session_translate'];
	
	$body = "<input type='hidden' name='session_translate' value='{$session_translate}' />";
	
	$body .= "<div class='selecttype'>";
	$body .= "<label>" . elgg_echo('translationbrowser:selecttypeexport') . "</label><br />";
	$body .= "<input type='radio' name='export[]' value='update' checked='checked'/>" . elgg_echo('translationbrowser:updatefile');
	$body .= "<input type='radio' name='export[]' value='generate' />" . elgg_echo('translationbrowser:generatefile');
	$body .= "</div>";
	
	$body .= "<p class='highlighter'><img src='{$vars["url"]}mod/translationbrowser/images/highlight.gif' /><a href='javascript:void(0)' onclick='highlight()'>" . elgg_echo('translationbrowser:highlight') . "</a></p>";
	
	$body .= "<div class='cont-button'>" . elgg_view('input/submit', array('value' => elgg_echo('translationbrowser:translate'))) . "</div>";
	
	foreach($data->lang_trans as $key => $word)
	{
		
		
		$body .= "<p><img class='flag' src='{$vars['url']}/mod/translationbrowser/flags/{$data->lang_code_trans}.gif' /><strong>" . elgg_echo($data->lang_code_trans) . "</strong></p>";
		$body .= "<div class='lang_trans'>" . nl2br($word) . "</div>";
		$word_to_trans = "";
		//First find in session
		if (isset($data->new_trans[$key]))
		{
			$word_to_trans = $data->new_trans[$key];
		}else if(isset($data->lang_to_trans[$key]))
		{
			$word_to_trans = $data->lang_to_trans[$key];
		} 
		
		$body .= "<p><img class='flag' src='{$vars['url']}/mod/translationbrowser/flags/{$data->lang_code_to_trans}.gif' /><strong>" . elgg_echo($data->lang_code_to_trans) . "</strong> <span>(" . elgg_echo("translationbrowser:canyouedit") . ")</span></p>";
		
		if ($word ==$word_to_trans)
		{
		$body .= elgg_echo('translationbrowser:translate:sametext');
		}

		$body .= "<textarea name='words[{$key}]' cols='50' rows='2'>{$word_to_trans}</textarea>";
	}
	
	$body .= "<div class='cont-button'>" . elgg_view('input/submit', array('value' => elgg_echo('translationbrowser:translate'))) . "</div>";
	
	echo "<div id='cont-words'>";
	echo  elgg_view('input/form', array('internalid' => 'browsertranslate', 'name' => 'browsertranslate', 'action' => "{$vars['url']}action/translationbrowser/translate", 'body' => $body));
	echo "</div>";

?>

<script type="text/javascript">

	function highlight()
	{
		jQuery('#browsertranslate textarea:empty').css('background','yellow');
		if(jQuery('#browsertranslate textarea:empty').length>0) 
		{ 
			jQuery('#browsertranslate textarea:empty:eq(0)').focus();
		} 
	}

</script>