<?php
$tokens = array(' 0 ', ' ', ', ', 'SELECT', false, '\n');
foreach ($tokens as $token) {
	
	echo "orginial: :".$token.":\n";
	
	$token = trim($token);
	
	echo 'trimmed :'.$token.":\n";
	
	if (empty($token)) {
		echo "token is empty\n";
	} else {
		echo "token is not empty\n";
	}

	if ($token) {
		echo "token is true\n";
	} else {
		echo "token is not true\n";
	}
	
	if (!$token) {
		echo "token is false\n";
	} else {
		echo "token is not false\n";
	}
	
	if ($token == "") {
		echo "token is equal an empty string\n";
	} else {
		echo "token is not equal an empty string\n";
	}
	
	if ($token === "") {
		echo "token is identic to an empty string\n";
	} else {
		echo "token is not identic to an empty string\n";
	}
	
	if ($token === false) {
		echo "token is identic to false\n";
	} else {
		echo "token is not identic to false\n";
	}
	
	if ($token === "0") {
	   echo "token is identic to 0\n";
	} else {
	   echo "token is not identic to 0\n";
	}
	
	if ($token == "0") {
	   echo "token is equal to 0\n";
	} else {
	   echo "token is not equal to 0\n";
	}
	
	echo "\n";
}