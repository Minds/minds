<?php

namespace Minds\tests\phpunit\mocks;

class MockAMQPChannel{

  public $callbacks = array();
  private $queue = array();

  public function exchange_declare($name, $type, $passive, $durable, $auto_delete){}

  public function queue_declare($name, $passive, $durable, $exclusive, $auto_delete){}

  public function queue_bind($queue, $exchange, $binder){}

  public function basic_publish($message, $exchange, $binder){
    $this->queue[$binder][] = $message;
  }

  public function basic_consume($queue, $exchange, $a, $b, $c, $d, $callback){
    foreach($this->queue[$queue] as $message){
      $this->callbacks[] = $callback;
      $callback($message);
    }

  }

  public function wait(){
  }

  public function close(){
    $this->callbacks = array();
  }

}
