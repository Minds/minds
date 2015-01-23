<?php

use Minds\Core\Events\Dispatcher;

class eventsTest extends \Minds_PHPUnit_Framework_TestCase {

    
    
    public function testModifyDefault() {
	
	// Register an event
	Dispatcher::register('test', 'return', function ($event) {
	    
	    $event->setResponse('bar');
	});
	
	
	// Test modification
	$this->assertEquals(
		Dispatcher::trigger('test','return',null, 'foo'),
		'bar'
	);
    }

}
