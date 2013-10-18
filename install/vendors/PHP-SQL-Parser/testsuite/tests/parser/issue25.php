<?php

require_once(dirname(__FILE__) . "/../../../php-sql-parser.php");
require_once(dirname(__FILE__) . "/../../test-more.php");

$parser = new PHPSQLParser();

$sql = "SELECT * FROM contacts WHERE contacts.id IN (SELECT email_addr_bean_rel.bean_id FROM email_addr_bean_rel, email_addresses WHERE email_addresses.id = email_addr_bean_rel.email_address_id AND email_addr_bean_rel.deleted = 0 AND email_addr_bean_rel.bean_module = 'Contacts' AND email_addresses.email_address IN (\"test@example.com\"))";
$p = $parser->parse($sql);
$expected = getExpectedValue(dirname(__FILE__), 'issue25.serialized');
eq_array($p, $expected, 'parenthesis problem on issue 25');

