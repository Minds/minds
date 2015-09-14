<?php

namespace Minds\tests\phpunit\mocks;

class MockCassandra{

  static $mocks = array();
  private $rows = array();

  public function get($key, $slice = NULL){
    //@todo listen to slice..
    if(isset($this->rows[$key]) && !empty($this->rows[$key]))
      return $this->rows[$key];

    return false;
  }

  public function multiget($keys, $slice = NULL){

    $rows = array();
    foreach($keys as $key){
      if($this->rows[$key]);
        $rows[$key] = $this->rows[$key];
    }

    if(count($rows) > 0)
      return $rows;

    return false;

  }

  public function insert($key, $data){
    if(!isset($this->rows[$key]))
      $this->rows[$key] = array();

    foreach($data as $k => $v){
      $this->rows[$key][$k] = $v;
    }

    return $key;
  }

  public function remove($key, $columns = array()){
    if(empty($columns)){
      unset($this->rows[$key]);
    } else {
      foreach($columns as $column){
        unset($this->rows[$key][$column]);
      }
    }
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
   * @param string $cf
   */
  static public function build($cf){
    if(!isset(self::$mocks[$cf]))
      self::$mocks[$cf] = new MockCassandra();
    return self::$mocks[$cf];
  }

}
