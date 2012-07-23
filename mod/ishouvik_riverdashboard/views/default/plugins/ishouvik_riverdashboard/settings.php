
<p>
<?php

echo "<h1>" . elgg_echo('ishouvik:riverdashboard:annoucement') . "</h1>" . elgg_view("ishouvik_riverdashboard/input/announcement", array("name"=>"params[announcement_content]", "value"=>$vars['entity']->announcement_code));

?>
</p>
