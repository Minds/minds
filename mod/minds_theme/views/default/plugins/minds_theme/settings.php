
<p>
<?php

echo "<h3>" . elgg_echo('minds:riverdashboard:annoucement') . "</h3>" . elgg_view("input/plaintext", array("name"=>"params[announcement_content]", "value"=>$vars['entity']->announcement_code));

?>
</p>
