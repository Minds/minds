<?php
/**
 * Messenger Message
 */

namespace Minds\Plugin\Messenger\Entities;

use Minds\Core\Di\Di;
use Minds\Entities\DenormalizedEntity;

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

	public function encrypt()
	{
			Di::_()->get('Messenger\Encryption')
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

			$this->rowKey = "object:gathering:conversation:{$this->conversation->getIndexKey()}";
			return $this->saveToDb([
				'guid' => $this->guid,
				'messages' => $this->messages,
				'time_created' => $this->time_created ?: time(),
				'friendly_ts' => $this->friendly_ts
			]);
	}

}
