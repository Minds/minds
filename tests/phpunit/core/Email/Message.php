<?php

use Minds\Core\Email;
use Minds\Entities;
use Minds\tests\phpunit\mocks;

class MessageTest extends \Minds_PHPUnit_Framework_TestCase {

	public function testSetTo(){
		$user = new Entities\User();
		$user->name = "Mark Harding";
		$user->username = "mark";
		$user->setEmail('mark@minds.com');

		$message = new Email\Message();
		$this->assetEquals($message->setTo($user), $message);
		$this->assetEquals($message->to, array(
			array(
				'name' => $user->name,
				'email' => $user->getEmail()
			)
		));
	}

}
