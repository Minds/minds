<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

// Get plugin settings
$allowhtml = elgg_get_plugin_setting('market_allowhtml', 'market');
$currency = elgg_get_plugin_setting('market_currency', 'market');
$numchars = elgg_get_plugin_setting('market_numchars', 'market');
if($numchars == ''){
	$numchars = '250';
}

// Set title, form destination
$title = elgg_echo("market:addpost");
$tags = "";
$title = "";
$price = "";
$custom = "";
$description = "";
if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

// Just in case we have some cached details
if (isset($vars['markettitle'])) {
	$title = $vars['markettitle'];
	$body = $vars['marketbody'];
	$price = $vars['marketprice'];
	$custom = $vars['marketcustom'];
	$tags = $vars['markettags'];
}


?>
<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		cntfield.value = maxlimit - field.value.length;
	}
}
function acceptTerms() {
	error = 0;
	if(!(document.marketForm.accept_terms.checked) && (error==0)) {		
		alert('<?php echo elgg_echo('market:accept:terms:error'); ?>');
		document.marketForm.accept_terms.focus();
		error = 1;		
	}
	if(error == 0) {
		document.marketForm.submit();	
	}
}
</script>
<?php
echo "<label>";
echo elgg_echo("title");
echo "&nbsp;<small><small>" . elgg_echo("market:title:help") . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "markettitle",
				"value" => $title,
				));
echo "</label></p>";

$marketcategories = elgg_view('market/marketcategories',$vars);
if (!empty($marketcategories)) {
	echo "<p>{$marketcategories}</p>";
}

if(elgg_get_plugin_setting('market_custom', 'market') == 'yes'){
	$marketcustom = elgg_view('market/custom',$vars);
	if (!empty($marketcustom)) {
		echo "<p>{$marketcustom}</p>";
	}
}

echo "<p><label>" . elgg_echo("market:text") . "<br>";
if ($allowhtml != 'yes') {
	echo "<small><small>" . sprintf(elgg_echo("market:text:help"), $numchars) . "</small></small><br />";
	echo "<textarea name='marketbody' class='mceNoEditor' rows='8' cols='40' onKeyDown='textCounter(document.marketForm.marketbody,document.marketForm.remLen1,{$numchars}' onKeyUp='textCounter(document.marketForm.marketbody,document.marketForm.remLen1,{$numchars})'>{$body}</textarea><br />";
	echo "<div class='market_characters_remaining'><input readonly type='text' name='remLen1' size='3' maxlength='3' value='{$numchars}' class='market_charleft'>" . elgg_echo("market:charleft") . "</div>";
} else {
	echo elgg_view("input/longtext", array("name" => "marketbody", "value" => $body));
}
echo "</label></p>";

echo "<p><label>" . elgg_echo("market:price") . "&nbsp;<small><small>" . elgg_echo("market:price:help", array($currency)) . "</small></small><br />";
echo elgg_view("input/text", array(
				"name" => "marketprice",
				"value" => $price,
				));
			
echo "</label></p>";

echo "<p><label>" . elgg_echo("market:tags") . "&nbsp;<small><small>" . elgg_echo("market:tags:help") . "</small></small><br />";
echo elgg_view("input/tags", array(
				"name" => "markettags",
				"value" => $tags,
				));
echo "</label></p>";

echo "<p><label>" . elgg_echo("market:uploadimages") . "<br /><small><small>" . elgg_echo("market:imagelimitation") . "</small></small><br />";
echo elgg_view("input/file",array('name' => 'upload'));
echo "</label></p>";

echo "<p><label>" . elgg_echo('access') . "&nbsp;<small><small>" . elgg_echo("market:access:help") . "</small></small><br />";
echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));
echo "</label></p>";

echo "<p>";
// Terms checkbox and link
$termslink = elgg_view('output/url', array(
			'href' => "mod/market/terms.php",
			'text' => elgg_echo('market:terms:title'),
			'class' => "elgg-lightbox",
			));
$termsaccept = sprintf(elgg_echo("market:accept:terms"),$termslink);
echo "</p>";
echo "<input type='checkbox' name='accept_terms'><label>{$termsaccept}</label></p>";

echo elgg_view('input/submit', array('name' => 'submit', 'text' => elgg_echo('save')));


