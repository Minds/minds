<?php
	class inviteForm{
		
		private $contacts;
		private $website_name;
		private $subject;
		private $message;
		
		public function __construct(array $contacts){
			
			global $email_from_field,$email_subject,$email_body,$website_name;
			
			$this->website_name = $website_name;
			$this->from = $email_from_field;
			$this->subject = $email_subject;
			$this->message = $email_body;
			
			if(isset($_SESSION['oauth']['default_message'])){
				$this->message = $_SESSION['oauth']['default_message'];
				unset($_SESSION['oauth']['default_message']);
			}
			
			$this->contacts = $contacts;
		}
		
		public function setMessage($txt){
			$this->message = $txt;
		}
		
		public function setWebsiteName($txt){
			$this->website_name = $txt;
		}
		
		public function display(){
			
			$template = file_get_contents(dirname(__file__)."/../template/form");
			
			$checkboxes = "";
			foreach($this->contacts as $contact){
				$checkboxes .= "<div class=\"contact_container\"><input type=\"checkbox\" name=\"emails[]\" class=\"chk\" value=\"".$contact['email']."\" /> ".$contact['name']."</div>";
			}
			
			$template = str_replace("{website_name}",$this->website_name,$template);
			$template = str_replace("{message}",$this->message,$template);
			$template = str_replace("{checkboxes}",$checkboxes,$template);
			
			return $template;
		}
		
	}
?>
