<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Entities;

class Report extends DenormalizedEntity
{
    protected $db;

    protected $guid;
    protected $entity_guid;
    protected $time_created;
    protected $reporter_guid;
    protected $entity_luid;
    protected $owner_guid;
    protected $state;
    protected $action = '';
    protected $reason = 0;
    protected $reason_note = '';
    protected $appeal_note = '';

    protected $exportableDefaults = [
        'guid',
        'entity_guid',
        'time_created',
        'reporter_guid',
        'entity_luid',
        'owner_guid',
        'state',
        'action',
        'reason',
        'reason_note',
        'appeal_note',
    ];

    public function __construct($db = null)
    {
        $this->db = null;
    }

    public function loadFromGuid($guid)
    {
        throw new \Exception('Use Reports\Repository::getRow()');
    }

    public function saveToDb($data)
    {
        throw new \Exception('Use Reports\Repository');
    }

    public function delete()
    {
        throw new \Exception('Use Reports\Repository::delete()');
    }

    /**
     * @return mixed
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param mixed $guid
     */
    public function setGuid($guid)
    {
        if (is_object($guid) && method_exists($guid, 'value')) {
            $guid = $guid->value();
        }

        $this->guid = $guid;
    }


    /**
     * @return mixed
     */
    public function getEntityGuid()
    {
        return $this->entity_guid;
    }

    /**
     * @param mixed $entity_guid
     */
    public function setEntityGuid($entity_guid)
    {
        if (is_object($entity_guid) && method_exists($entity_guid, 'value')) {
            $entity_guid = $entity_guid->value();
        }

        $this->entity_guid = $entity_guid;
    }

    public function getEntityLuid()
    {
        return $this->entity_luid;
    }

    /**
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * @param mixed $time_created
     */
    public function setTimeCreated($time_created)
    {
        if (is_object($time_created) && method_exists($time_created, 'time')) {
            $time_created = $time_created->time();
        }

        $this->time_created = (int) $time_created;
    }

    /**
     * @return mixed
     */
    public function getReporterGuid()
    {
        return $this->reporter_guid;
    }

    /**
     * @param mixed $reporter_guid
     */
    public function setReporterGuid($reporter_guid)
    {
        if (is_object($reporter_guid) && method_exists($reporter_guid, 'value')) {
            $reporter_guid = $reporter_guid->value();
        }

        $this->reporter_guid = $reporter_guid;
    }

    /**
     * @return mixed
     */
    public function getOwnerGuid()
    {
        return $this->owner_guid;
    }

    /**
     * @param mixed $owner_guid
     */
    public function setOwnerGuid($owner_guid)
    {
        if (is_object($owner_guid) && method_exists($owner_guid, 'value')) {
            $owner_guid = $owner_guid->value();
        }

        $this->owner_guid = $owner_guid;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state ?: '';
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action ?: '';
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @param mixed $reason
     */
    public function setReason($reason)
    {
        $this->reason = $reason ?: '';
    }

    /**
     * @return mixed
     */
    public function getReasonNote()
    {
        return $this->reason_note;
    }

    /**
     * @param mixed $reason_note
     */
    public function setReasonNote($reason_note)
    {
        $this->reason_note = $reason_note ?: '';
    }

    /**
     * @return mixed
     */
    public function getAppealNote()
    {
        return $this->appeal_note;
    }

    /**
     * @param mixed $appeal_note
     */
    public function setAppealNote($appeal_note)
    {
        $this->appeal_note = $appeal_note ?: '';
    }

    public function export(array $keys = [])
    {
        $export = parent::export($keys);

        $remove = [];

        if (!Core\Session::isAdmin()) {
            $remove = [
                'state',
                'reporter_guid',
                'reason_note',
            ];

            $currentUser = Core\Session::getLoggedInUserGuid();

            if ($export['owner_guid'] != $currentUser) {
                $remove = array_merge($remove, [
                    'appeal_note',
                    'action',
                ]);
            }
        }

        foreach ($remove as $key) {
            unset($export[$key]);
        }

        if ($export['entity_luid'] || $export['entity_guid']) {
            $entity = Entities\Factory::build($export['entity_luid'] ?: $export['entity_guid']);

            $export['entityObj'] = $entity ? $entity->export() : null;

            if (isset($export['entityObj']['ownerObj'])) {
                $export['ownerObj'] = $export['entityObj']['ownerObj'];
            } elseif (isset($export['entityObj']['owner_guid'])) {
                $owner = Entities\Factory::build($export['entityObj']['owner_guid']);

                $export['ownerObj'] = $owner ? $owner->export() : null;
            }
        }

        if (isset($export['reporter_guid']) && $export['reporter_guid']) {
            $reporter = Entities\Factory::build($export['reporter_guid']);

            $export['reporterObj'] = $reporter ? $reporter->export() : null;
        }

        return $export;
    }

}
