<?php
/**
 * The core language file is in /languages/en.php and each plugin has its
 * language files in a languages directory. To change a string, copy the
 * mapping into this file.
 *
 * For example, to change the blog Tools menu item
 * from "Blog" to "Rantings", copy this pair:
 * 			'blog' => "Blog",
 * into the $mapping array so that it looks like:
 * 			'blog' => "Rantings",
 *
 * Follow this pattern for any other string you want to change. Make sure this
 * plugin is lower in the plugin list than any plugin that it is modifying.
 *
 * If you want to add languages other than English, name the file according to
 * the language's ISO 639-1 code: http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
 */

$mapping = array(
	'honeypot:color' => 'Background color of the register from',
        'honeypot:emailme' => 'Email me spammers address',
        'honeypot:emailaddress' => 'Email address to send spammers addresses to',
        'honeypot:spammercaught' => 'Spammer caught',
        'honeypot:spammerdetails' => 'The email address of the spammer is %s'
);

add_translation('en', $mapping);
