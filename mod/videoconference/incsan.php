<?php
function sanV(&$var, $file=1, $html=1, $mysql=1) //sanitize variable depending on use
{
	if (!$var) return;

	if (get_magic_quotes_gpc()) $var = stripslashes($var);
	
	if ($file)
	{
		$var=preg_replace("/\.{2,}/","",$var); //allow only 1 consecutive dot
		$var=preg_replace("/[^0-9a-zA-Z\.\-\s_]/","",$var); //do not allow special characters
	}
	
	if ($html&&!$file)
	{
		$var=strip_tags($var);
		$forbidden=array("<", ">");
		foreach ($forbidden as $search)  $var=str_replace($search,"",$var);
	}

	if ($mysql&&!$file) 
	{
		$forbidden=array("'", "\"", "´", "`", "\\", "%");
		foreach ($forbidden as $search)  $var=str_replace($search,"",$var);
		$var=mysql_real_escape_string($var);
	}
}
?>