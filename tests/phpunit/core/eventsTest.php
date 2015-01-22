<?php

use Minds\Core\events;

class eventsTest extends \Minds_PHPUnit_Framework_TestCase {

    
    
    public function testModifyDefault() {
	
	// Register an event
	events::register('test', 'return', function ($event) {
	    
	    $event->setResponse('bar');
	});
	
	
	// Test modification
	$this->assertEquals(
		events::trigger('test','return',null, 'foo'),
		'bar'
	);
    }

}
