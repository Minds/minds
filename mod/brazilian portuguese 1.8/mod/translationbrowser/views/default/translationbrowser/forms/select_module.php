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

$languages = $vars['languages'];
$modules = $vars['modules'];

$body = elgg_view(
            "input/translationbrowser_pulldown", array(
                'options_values' => $languages, 
                'name' => "languages", 
                "internalid" => "select-language", 
                'value' => elgg_echo("translationbrowser:default:language")
        ));

$body .= "<div id='language-selected'>".
         elgg_echo("translationbrowser:yourselectedlanguage").": ".
         "<img src={$vars['url']}mod/translationbrowser/flags/".
         elgg_echo("translationbrowser:default:language"). ".gif />" . 
         "<span>&nbsp; " . $languages[elgg_echo("translationbrowser:default:language")] . "</span>".
         "</div>";

$body .= "<div id='select-module'>" . 
            elgg_echo("translationbrowser:selectmodule") . 
         "</div>";

$body .= "<div id='cont-countries'>";

foreach($modules as $key => $opt)
{
    $body .= elgg_view(
                    "input/translationbrowser_button", array(
                        'value' => elgg_echo('translationbrowser:translate'), 
                        'type' => 'button', 
                        'class' => 'submit_button'
             ));
    $body .= "<label>";
    $body .= "<input type='radio' value='".base64_encode($opt)."' name='modules' />";
    $body .= "<I> <span> {$key} </span> </I>";
    $body .= "<span> {$opt} </span>";
    $body .= "</label>";
}

$body .= "</div>";
echo elgg_view(
       'input/form', array(
             'internalid' => 'browsertranslate', 
             'name' => 'browsertranslate', 
             'action' => "{$vars['url']}action/translationbrowser/get_text",
             'body' => $body
     ));
?>

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function()
    {
        $('#browsertranslate select[name="languages"]').change(function() {
            putFlag(this);
        });

        $('input.submit_button').click(function()
        {
            $('#browsertranslate').submit();
        });

        $('#cont-countries label').click(function()
        {
            //remove all class with name selected
            $('#cont-countries label').removeClass('selected');
            //remove all buttons
            $('#cont-countries input.submit_button').hide();
            $(this).addClass('selected');
            $(this).prev('input.submit_button').show();
            $(this).children()[0].checked = true;
            //$(this).find('input:radio').click();
        });
	});
	
	function putFlag(oObject)
	{
        var lang = '<?php echo elgg_echo("translationbrowser:yourselectedlanguage");?>';
        var url = '<?php echo $vars['url'] ?>mod/translationbrowser/flags/'; 
        var sLangCode = $(oObject).find('option:selected').val().split('#');
        var sLangCode = sLangCode[0];
        if(sLangCode != 0) 
        {
            $('#language-selected').html(
                    lang + ": <img src='"+ url + sLangCode + ".gif' />" + 
                    "<span>&nbsp; " + $(oObject).find('option:selected').text() + 
                    "</span>"
            );
        }
        else
        {
            $('#language-selected').html("");
        }
	}
//]]>
</script>
