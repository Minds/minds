<?php
/**
 * Messenger Conversation
 */

namespace Minds\Plugin\Messenger\Entities;

use Minds\Core\Session;
use Minds\Core\Di\Di;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\User;

class Conversation extends DenormalizedEntity{

	protected $rowKey;

	protected $exportableDefaults = [
		'guid', 'type', 'subtype', 'unread', 'online', 'ts'
	];
	protected $type = 'messenger';
	protected $subtype = 'conversation';
	protected $guid;
	public $ts;
	protected $unread = 0;
	protected $participants = [];
	protected $online = false;

	public function __construct($db = NULL)
	{
			parent::__construct($db);
			$this->rowKey = "object:gathering:conversations:" . Session::getLoggedInUser()->guid;
	}

    public function loadFromGuid($guid)
    {
        try{
            parent::loadFromGuid($guid);
        } catch(\Exception $e) {
            //conversation does not exist
            $participants = explode(':', $guid);
            foreach($participants as $participant){
                $this->setParticipant($participant);
            }
        }
        return $this;
    }


	public function setParticipant($guid)
	{
			if($guid instanceof User){
					$guid = $guid->user;
			}
			if(!isset($this->participants[$guid])){
					$this->participants[$guid] = $guid;
			}
			return $this;
	}

    public function clearParticipants()
    {
        $this->participants = [];
        return $this;
    }

	public function getParticipants()
	{
			return $this->participants ?: [];
	}

	public function setOnline($boolean)
	{
			$this->online = $boolean;
			return $this;
	}

	public function getGuid()
	{
			if($this->guid){
					return $this->guid;
			}
			return $this->permutateGuid($this->getParticipants());
	}

	public function buildSocketRoomName()
	{
		if (strpos($this->getGuid(), ':') !== false) {
			return 'conversation:' . $this->getGuid();
		}

		// Fallback
		return 'conversation:' . $this->permutateGuid($this->getParticipants());
	}

	public function setGuid($guid)
	{
			$this->guid = $guid;

			if (strpos($guid, ':') !== false) {
				$participants = explode(':', $guid);
				foreach($participants as $participant){
					$this->setParticipant($participant);
				}
			}
	}

	private function permutateGuid($input = [])
	{
			$result = "";
			asort($input);
			foreach($input as $key => $guid){
					$result .= $result ? ":$guid" : $guid;
			}
			return $result;
	}

	public function saveToLists()
	{
			return $this->saveToParticipants($this->participants);
	}

	public function saveToParticipants($participants = [])
	{
			foreach($participants as $participant_guid){
					$this->db->insert("object:gathering:conversations:$participant_guid", [
						$this->getGuid() => json_encode([
							'ts' => $this->ts,
							'unread' => $this->unread ?: 0,
							'participants' => array_values($this->participants)
						])
					]);
			}
	}

	public function save()
	{
			return $this->saveToLists();
	}

	/**
	 * Marks the conversation as unread for participant
	 * @param int $marker
	 * @return $this
	 */
	public function markAsRead($marker)
	{
			$this->unread = false;
			$this->saveToParticipants([$marker]);
			return $this;
	}

	/**
	 * Marks the conversation as unread for other participants
	 * @param int $marker
	 * @return $this
	 */
	public function markAsUnread($marker)
	{
			$this->unread = true;
			$this->ts = time();
			$this->saveToParticipants(array_diff($this->participants, [$marker]));
			return $this;
	}

	public function export($keys = [])
	{
			$export = parent::export($keys);

			foreach($this->participants as $user_guid){
					if($user_guid != Session::getLoggedinUser()->guid){
							$user = new User($user_guid);
							$export['participants'][$user_guid] = $user->export();
							//$export['guid'] = (string) $user_guid; //for legacy support
							$export['name'] = $user->name;
							$export['username'] = $user->username;
					}
			}
			$export['participants'] = array_values($export['participants']); //make sure we are an array, not an object
			$export['socketRoomName'] = $this->buildSocketRoomName();

			return $export;
	}

}
