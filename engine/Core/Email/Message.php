<?php
/**
 * Message item
 */
namespace Minds\Core\Email;

class Message{

  public $from = array();
  public $to = array();
  public $subject = "";
  public $html = "";

  public function __construct(){
    $this->init();
  }

  private function init(){
    $this->from = array(
      'name' => "Minds",
      'email' => "info@minds.com"
    );
  }

  /**
   * Set from data
   * @param string $email
   * @param string $name
   * @return $this
   */
  public function setFrom($email, $name = "Minds"){
    $this->from = array(
      'name' => $name,
      'email' => $email
    );
    return $this;
  }

  /**
   * Set to data
   * @param Entities\User $user
   * @return $this
   */
  public function setTo($user){
    $this->to[] = array(
      'name' => $user->name,
      'user' => $user->getEmail()
    );
    return $this;
  }

  /**
   * Set subject data
   * @param string $subject
   * @return $this
   */
  public function setSubject($subject){
    $this->subject = $subject;
    return $this;
  }

  /**
   * Set html data
   * @param string $html
   * @return $this
   */
  public function setHtml($html){
    $this->html = $html;
  }

  /**
   * Set html data
   * @return $html
   */
  public function buildHtml(){
    return $this->html;
  }

}
