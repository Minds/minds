<?php
namespace Minds\Entities;

use Minds\Core;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;

/**
 * Report Entity (Entities reported by users)
 */
class Wire extends NormalizedEntity
{

    protected $type = 'wire';
    protected $guid;
    protected $entity;
    protected $from;
    protected $to;
    protected $time_created;
    protected $method;
    protected $transaction_id;

    protected $indexes = [];

    protected $exportableDefaults = [
        'type',
        'guid',
        'entity',
        'from',
        'time_created',
        'method',
        'transaction_id'
    ];

    /**
     * Saves the entity
     * @return mixed|bool
     */
    public function save()
    {
        if (!$this->entity) {
            throw new \Exception('Missing wire entity');
        }

        if (!$this->method) {
            throw new \Exception('Missing wire method');
        }

        if (!$this->getGuid()) {
            $this->guid = Core\Guid::build();
        }

        $data = [
            'type' => $this->type,
            'guid' => $this->guid,
            'entity' => $this->entity ? $this->entity->export() : null,
            'from' => $this->from ? $this->from->export() : null,
            'time_created' => $this->time_created,
            'method' => $this->method,
            'transaction_id' => $this->transaction_id ?: ''
        ];

        $saved = $this->saveToDb($data);

        $this->indexes = [
          'wire:sent:' . $this->from->guid,
          'wire:received:' . $this->to->guid,
          'wire:entity:' . $this->entity->guid
        ];
        $this->saveToIndex();

        return $saved;
    }

    /**
     * Writes an array of data to DB
     * @param  array $data
     * @return mixed|bool
     */
    public function saveToDb($data)
    {
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
     * Sets `from`
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
     * Gets `to`
     * @return User
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets `from` (reporting user)
     * @param  mixed $from
     * @return $this
     */
    public function setTo($to)
    {
        if (!is_object($to)) {
            $from = Entities\Factory::build($to);
        }

        $this->to = $to;
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
     * Gets `method`
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets `method`
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

}
