<?php
/**
 * Email mailer
 */
namespace Minds\Core\Email;

use Minds\Core;
use Minds\Entities;
use PHPMailer;

class Mailer
{
    private $mailer;
    private $stats;

    public function __construct($mailer = null)
    {
        $this->mailer = $mailer;
        $this->setup();
        $this->stats = [
          'sent' => 0,
          'failed' => 0
        ];
    }

    private function setup()
    {
        $this->mailer->isSMTP();
        $this->mailer->SMTPKeepAlive = true;
        $this->mailer->Host = \elgg_get_plugin_setting('phpmailer_host', 'phpmailer');
        $this->mailer->Auth = \elgg_get_plugin_setting('phpmailer_smtp_auth', 'phpmailer');
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = \elgg_get_plugin_setting('phpmailer_username', 'phpmailer');
        $this->mailer->Password = \elgg_get_plugin_setting('phpmailer_password', 'phpmailer');
        $this->mailer->SMTPSecure = "ssl";
        $this->mailer->Port = \elgg_get_plugin_setting('ep_phpmailer_port', 'phpmailer');
    }

    /**
     * Send an email
     * @param Message $message
     * @return $this
     */
    public function send($message)
    {
      $this->mailer->ClearAllRecipients();
      $this->mailer->ClearAttachments();

      $this->mailer->From = $message->from['email'];
      $this->mailer->FromName = $message->from['name'];

      foreach ($message->to as $to) {
          $this->mailer->AddAddress($to['email'], $to['name']);
      }

      $this->mailer->Subject = $message->subject;

      $this->mailer->IsHTML(true);
      $this->mailer->Body = $message->buildHtml();

      if ($this->mailer->Send()) {
          $this->stats['sent']++;
      } else {
          $this->stats['failed']--;
      }

      return $this;
    }

    public function __destruct()
    {
        if($this->mailer){
            $this->mailer->SmtpClose();
        }
    }
}
