<?php

namespace Minds\tests\phpunit\mocks;

class MockAMQPConnection{

  public $channel;

  public function channel(){
    return new MockAMQPChannel();
  }

  public function isConnected(){
    return true;
  }

  public function close(){}

}
