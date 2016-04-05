<?php
/**
 * Message item
 */
namespace Minds\Core\Email;

class Message
{
    public $from = array();
    public $to = array();
    public $subject = "";
    public $html = "";

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->from = [
          'name' => "Minds",
          'email' => "info@minds.com"
        ];
    }

  /**
   * Set from data
   * @param string $email
   * @param string $name
   * @return $this
   */
  public function setFrom($email, $name = "Minds")
  {
      $this->from = [
        'name' => $name,
        'email' => $email
      ];
      return $this;
  }

  /**
   * Set to data
   * @param Entities\User $user
   * @return $this
   */
  public function setTo($user)
  {
      $this->to[] = [
        'name' => $user->name,
        'email' => $user->getEmail()
      ];
      return $this;
  }

  /**
   * Set subject data
   * @param string $subject
   * @return $this
   */
  public function setSubject($subject)
  {
      $this->subject = $subject;
      return $this;
  }

  /**
   * Set html data
   * @param string $html
   * @return $this
   */
  public function setHtml(Template $html)
  {
      $this->html = $html;
  }

  /**
   * Set html data
   * @return $html
   */
  public function buildHtml()
  {
      return $this->html->render();
  }
}
