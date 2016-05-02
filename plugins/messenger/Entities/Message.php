<?php
/**
 * Messenger Message
 */

namespace Minds\Plugin\Messenger\Entities;

use Minds\Core\Di\Di;
use Minds\Entities\DenormalizedEntity;
use Minds\Plugin\Messenger;

class Message extends DenormalizedEntity{

	protected $conversation;

	protected $exportableDefaults = [
		'guid', 'friendly_ts', 'message', 'messages', 'type', 'subtype'
	];
	protected $type = 'object';
	protected $subtype = 'message';
	protected $friendly_ts;
	protected $message;
	protected $messages = [];
	protected $encrypted = true;

	public function setConversation($conversation)
	{
			$this->conversation = $conversation;
			return $this;
	}

	public function setMessage($message)
	{
			$this->message = $message;
			$this->encrypted = false;
			return $this;
	}

	public function getMessage($user_guid = NULL)
	{
			if($this->encryption && $user_guid){
					return $this->messages[$user_guid];
			}
			return $this->message;
	}

	public function setMessages($user_guid, $message, $encrypted = true)
	{
			$this->messages[$user_guid] = $message;
			$this->encrypted = $encrypted;
			return $this;
	}

	public function encrypt()
	{
			//Di::_()->get('Messenger\Encryption')
			(new Messenger\Core\Encryption\OpenSSL())
				->setConversation($this->conversation)
				->setMessage($this)
				->encrypt();
			$this->encrypted = true;
			return $this;
	}

	public function decrypt($user, $password)
	{
			Di::_()->get('Messenger\Encryption')
				->setMessage($this)
				->setUser($user)
				->setUnlockPassword($password)
				->decrypt();
			$this->encrypted = false;
			return $this;
	}

	public function save()
	{
			if(!$this->encrypted){
					throw new Exception('You can not save unencrypted messages');
			}
			$this->getGuid();
			$this->rowKey = "object:gathering:conversation:{$this->conversation->getGuid()}";
			$this->saveToDb([
				'guid' => $this->getGuid(),
				'messages' => $this->messages,
				'time_created' => $this->time_created ?: time(),
				//'friendly_ts' => $this->friendly_ts
			]);
			//var_dump($this->rowKey); exit;
	}

}
