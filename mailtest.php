<?php

require_once(dirname(__FILE__) . "/engine/start.php");

 phpmailer_send(
			'minds@minds.com',
			'minds',
			'mark@Minds.com',
			'',
			'testing',
			'just as test');
