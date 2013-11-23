<?php

	echo "<div>";
	echo elgg_view("input/text", array("name" => "q", "value" => get_input("q"), "placeholder" => elgg_echo("search")));
	echo "</div>";