<div>
<p>
<?php

echo "<h3>" . elgg_echo('minds:riverdashboard:annoucement') . "</h3>" . elgg_view("input/plaintext", array("name"=>"params[announcement_content]", "value"=>$vars['entity']->announcement_code));

?>
</p>
</div><br/>

<div>
<h3> SEO Tags </h3>
<p>
<b> Description (used if not defined by an object)</b><br/>
<?php

echo elgg_view("input/plaintext", array("name"=>"params[default_description]", "value"=>elgg_get_plugin_setting('default_description', 'minds_theme')));

?>
</p>

<p>
<b> Keywords (used if not defined by an object)</b><br/>
<?php

echo elgg_view("input/plaintext", array("name"=>"params[default_keywords]", "value"=>elgg_get_plugin_setting('default_keywords', 'minds_theme')));

?>
</p>
</div>

<div>
<h3> Minds Quota </h3>
<p>

</p>
</div><br/>