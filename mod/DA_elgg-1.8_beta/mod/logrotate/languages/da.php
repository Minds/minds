<?php
/**
 * Elgg log rotator Danish language pack.
 *
 * @package ElggLogRotate
 */

$danish = array(	
	'logrotate:period' => 'Hvor ofte skal system loggen gemmes?',
	
	'logrotate:weekly' => 'En gang om ugen',
	'logrotate:monthly' => 'En gang om måneden',
	'logrotate:yearly' => 'En gang om året',
	
	'logrotate:logrotated' => "Log roteret\n",
	'logrotate:lognotrotated' => "Der opstod en fejl under rotering af log\n",

	'logrotate:date' => 'Slet arkiverede logs ældre end en/et',

	'logrotate:week' => 'uge',
	'logrotate:month' => 'måned',
	'logrotate:year' => 'år',
		
	'logrotate:logdeleted' => "Log slettet\n",
	'logrotate:lognotdeleted' => "Fejl under sletning af log\n",
			
);
				
add_translation('da',$danish);

?>
