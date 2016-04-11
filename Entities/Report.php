<?php
/**
 * User reported publications
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;

class Report extends DenormalizedEntity
{
    protected $rowKey = 'reports';

    protected $type = 'report';
    protected $guid;
    protected $entity;
    protected $from;
    protected $subject;
    protected $time_created;
    protected $state;
    protected $action;
    protected $read_only = false;

    private $valid_states = [
        'review', 'archive', 'history'
    ];
    protected $dirty_state = false;

    protected $exportableDefaults = [
        'type',
        'guid',
        'entity',
        'from',
        'subject',
        'time_created',
        'state',
        'action',
        'read_only',
    ];

    public function save()
    {
        if (!$this->entity) {
            throw new \Exception('Missing report entity');
        }

        if (!$this->state) {
            throw new \Exception('Missing report state');
        }

        if (!$this->getGuid()) {
            $this->guid = Core\Guid::build();
        }

        $data = [
            'type' => $this->type,
            'guid' => $this->guid,
            'entity' => $this->entity ? $this->entity->export() : null,
            'from' => $this->from ? $this->from->export() : null,
            'subject' => $this->subject,
            'time_created' => $this->time_created,
            'state' => $this->state,
            'action' => $this->action,
            'read_only' => (bool) $this->read_only
        ];

        return $this->saveToDb($data);
    }

    public function saveToDb($data) {

        if ($this->dirty_state) {
            $states = $this->valid_states;

            foreach ($states as $state) {
                if ($state == $data['state']) {
                    continue;
                }

                $this->db->removeAttributes($this->rowKey . ':' . $state, [ $data['guid'] ]);
            }
        }

        $this->db->insert($this->rowKey . ':' . $data['state'], [ $this->guid => json_encode($data) ]);

        return parent::saveToDb($data);
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getGuid()
    {
        return $this->guid;
    }

    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->entity = $entity;
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        if (!is_object($from)) {
            $from = Entities\Factory::build($from);
        }

        $this->from = $from;
        return $this;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function getTimeCreated()
    {
        return $this->time_created;
    }

    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        if (!in_array($state, $this->valid_states)) {
            throw new \UnexpectedValueException('Invalid state');
        }

        if ($this->state != $state) {
            $this->dirty_state = true;
        }

        $this->state = $state;
        return $this;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getReadOnly()
    {
        return $this->read_only;
    }

    public function setReadOnly($read_only)
    {
        $this->read_only = (bool) $read_only;
        return $this;
    }
}
