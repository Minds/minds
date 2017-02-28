<?php
namespace Minds\Entities;

use Minds\Entities\DenormalizedEntity;

use Minds\Core;

/**
 * Opt-In Request Entity
 */
class OptInRequest extends DenormalizedEntity
{
    protected $type = 'opt_in_request';
    protected $program;
    protected $guid;
    protected $time_created;
    protected $from;
    protected $message;

    protected $exportableDefaults = [
        'type',
        'program',
        'guid',
        'time_created',
        'from',
        'message',
    ];

    /**
     * Load entity data from a GUID
     * @param  $guid
     * @return $this
     * @throws \Exception
     */
    public function loadFromGuid($guid)
    {
        if (!$this->rowKey) {
            $this->rowKey = 'opt_in_requests:queue';
        }

        parent::loadFromGuid($guid);
    }

    /**
     * Writes the entity onto persistent storage
     * @return $this
     */
    public function save()
    {
        if (!$this->program) {
            throw new \UnexpectedValueException('Missing program');
        }

        if (!$this->from) {
            throw new \UnexpectedValueException('Missing user');
        }

        // generate a GUID (if not present) before saving
        $this->getGuid();

        $data = [
            'type' => $this->type,
            'program' => $this->program,
            'guid' => $this->guid,
            'message' => $this->message,
            'time_created' => $this->time_created,
            'from' => $this->from,
        ];

        // Might-be-exportable properties
        foreach (['from'] as $exportable) {
            if (is_object($data[$exportable]) && method_exists($data[$exportable], 'export')) {
                $data[$exportable] = $data[$exportable]->export();
            }
        }

        $from = $this->from;

        if (isset($this->from->guid)) {
            $from = $this->from->guid;
        } elseif (isset($this->from['guid'])) {
            $from = $this->from['guid'];
        }

        $this->rowKey = 'opt_in_requests:queue';
        $this->saveToDb($data);

        $this->rowKey = 'opt_in_requests:queue:' . $this->program;
        $this->saveToDb($data);

        $this->rowKey = 'opt_in_requests:user:' . $from . ':' . $this->program;
        $this->saveToDb($data);

        return $this;
    }

    /**
     * Delete the request
     * @return bool
     */
    public function delete()
    {
        if (!$this->guid) {
            throw new \Exception('Cannot delete ephemeral row');
        }

        $from = $this->from;

        if (isset($this->from->guid)) {
            $from = $this->from->guid;
        } elseif (isset($this->from['guid'])) {
            $from = $this->from['guid'];
        }

        $this->rowKey = 'opt_in_requests:queue';
        parent::delete();

        $this->rowKey = 'opt_in_requests:queue:' . $this->program;
        parent::delete();

        $this->rowKey = 'opt_in_requests:user:' . $from . ':' . $this->program;
        parent::delete();

        return true;
    }

    /**
     * Returns the value of `type` property
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the value of `type` property
     * @param $type mixed
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

     /**
     * Returns the value of `program` property
     * @return mixed
     */
    public function getProgram()
    {
        return $this->program;
    }

    /**
     * Sets the value of `program` property
     * @param $program mixed
     * @return $this
     */
    public function setProgram($program)
    {
        $this->program = $program;
        return $this;
    }

    /**
     * Returns the value of `type` property. Generates it if doesn't exist
     * @return mixed
     */
    public function getGuid()
    {
        if (!$this->guid) {
            $this->guid = Core\Guid::build();
            $this->time_created = time();
        }

        return $this->guid;
    }

    /**
     * Sets the value of `guid` property
     * @param $guid mixed
     * @return $this
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * Returns the value of `time_created` property
     * @return mixed
     */
    public function getTimeCreated()
    {
        return $this->time_created;
    }

    /**
     * Sets the value of `time_created` property
     * @param $time_created mixed
     * @return $this
     */
    public function setTimeCreated($time_created)
    {
        $this->time_created = $time_created;
        return $this;
    }

    /**
     * Returns the value of `from` property
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets the value of `from` property
     * @param $from mixed
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = is_numeric($from) ? Factory::build($from) : $from;
        return $this;
    }

    /**
     * Returns the value of `message` property
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets the value of `message` property
     * @param $message string
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
