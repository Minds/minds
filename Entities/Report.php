<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;

/**
 * Report Entity (Entities reported by users)
 */
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

    /**
     * Saves the entity
     * @return mixed|bool
     */
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

    /**
     * Writes an array of data to DB
     * @param  array $data
     * @return mixed|bool
     */
    public function saveToDb($data)
    {
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

    /**
     * Gets `type`
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets `type`
     * @param  string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets `guid`
     * @return int|string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Sets `guid`
     * @param  int|string $guid
     * @return $this
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Gets `entity` (the reported entity)
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Sets `entity` (the reported entity)
     * @param  mixed $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->entity = $entity;
        return $this;
    }

    /**
     * Gets `from` (reporting user)
     * @return User
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets `from` (reporting user)
     * @param  mixed $from
     * @return $this
     */
    public function setFrom($from)
    {
        if (!is_object($from)) {
            $from = Entities\Factory::build($from);
        }

        $this->from = $from;
        return $this;
    }

    /**
     * Gets `subject`
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets `subject`
     * @param  string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Gets `time_created`
     * @return int
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * Sets `time_created`
     * @param int $time_created
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    /**
     * Gets `state`
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Gets `state`
     * @param string $state
     */
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

    /**
     * Gets `action`
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Sets `action`
     * @param  string $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Gets `read_only` flag
     * @return bool
     */
    public function getReadOnly()
    {
        return $this->read_only;
    }

    /**
     * Sets `read_only` flag
     * @param bool  $read_only
     * @return $this
     */
    public function setReadOnly($read_only)
    {
        $this->read_only = (bool) $read_only;
        return $this;
    }
}
