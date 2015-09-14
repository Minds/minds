<?php

namespace Minds\tests\phpunit\mocks;

class MockCassandraCQL{

  static $mocks = array();
  private $rows = array();

  public function prepare($cql){

    return array(
      'id' => uniqid(),
      'metadata' => array(
        'columns' => array()
      ),
      'values' => array(

      ),
      'string' => $cql
    );
  }

  public function executeAsync($prepared, $request){

  }

  /**
   * Preload the mock
   * @param array $data
   * @return void
   */
  public function preload($data){
    $this->rows = array_merge($this->rows, $data);
  }

  /**
   * Makes the mock reusable
   */
  static public function build(){
    if(!isset(self::$mocks[0]))
      self::$mocks[0] = new MockCassandraCQL();
    return self::$mocks[0];
  }

}
