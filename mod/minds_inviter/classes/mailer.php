<?php
	class mailer{
		
		private $from;
		private $subject;
		private $message;
		private $emails;
		
		public function __construct(array $emails){
			
			global $email_from_field,$email_subject,$email_body,$website_name;
			
			$this->from = $email_from_field;
			$this->subject = $email_subject;
			$this->message = $email_body;
			
			$this->emails = $emails;
		}
		
		public function setFrom($from){
			$this->from = $from;
		}
		
		public function setSubject($subject){
			$this->subject = $subject;
		}
		
		public function setMessage($message){
			$this->message = $message;
		}
		
		public function send(){
			$headers = "From: ".$this->from."\r\n" ."X-Mailer: php";
			if(count($this->emails)>0){
				foreach($this->emails as $email){
					mail($email, $this->subject,$this->message, $headers);
				}
			}
		}
	}
?>
