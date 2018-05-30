<?php


namespace Minds\Core\Email;

use Minds\Core;
use Minds\Entities\User;

class EmailSubscribersIterator implements \Iterator
{
    private $cursor = -1;

    private $campaign;
    private $topic;
    private $value;

    private $limit = 200;
    public $offset = "";
    private $data = [];
    private $dryRun = false;

    private $valid = true;

    /** @var Repository */
    private $repository;
    /** @var Core\EntitiesBuilder */
    private $builder;

    public function __construct($repository = null, $entitiesBuilder = null)
    {
        $this->repository = $repository ?: Core\Di\Di::_()->get('Email\Repository');
        $this->builder = $entitiesBuilder ?: Core\Di\Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param mixed $campaign
     * @return EmailSubscribersIterator
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
        return $this;
    }

    /**
     * @param mixed $topic
     * @return EmailSubscribersIterator
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @param mixed $value
     * @return EmailSubscribersIterator
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }


    public function setOffset($offset = '')
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @param mixed $value
     * @return EmailSubscribersIterator
     */
    public function setDryRun($value)
    {
        $this->dryRun = $value;

        if ($this->dryRun) {
            $this->data = [
                new User('mark'),
                //new User('jack'),
                //new User('john'),
                //new User('ottman')
            ];
        }
        return $this;
    }

    /**
     * Fetch all the users who are subscribed to a certain email campaign/topic
     */
    public function getSubscribers()
    {
        $this->data = [];
        $this->cursor = 0;

        if (!isset($this->offset) || $this->dryRun) {
            $this->valid = false;
            return;
        }
        $options = [
            'campaign' => $this->campaign,
            'topic' => $this->topic,
            'value' => $this->value,
            'limit' => $this->limit,
            'offset' => base64_decode($this->offset)
        ];

        $result = $this->repository->getList($options);

        if (!$result || !$result['data'] || count($result['data']) === 0) {
            $this->valid = false;
            return;
        }

        $this->offset = $result['next'] !== '' ? $result['next'] : null;

        $guids = array_map(function ($item) {
            return $item->getUserGuid();
        }, $result['data']);

        $this->valid = true;
        $users = $this->builder->get(['guids' => $guids]);

        if (!$users) {
            $this->valid = false;
            return;
        }

        foreach ($users as $user) {
            $this->data[] = $user;
        }

        return $this;
    }

    /**
     * Rewind the array cursor
     * @return null
     */
    public function rewind()
    {
        if ($this->cursor >= 0) {
            $this->getSubscribers();
        }
        $this->next();
    }

    /**
     * Get the current cursor's data
     * @return mixed
     */
    public function current()
    {
        return $this->data[$this->cursor];
    }

    /**
     * Get cursor's key
     * @return mixed
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Goes to the next cursor
     * @return null
     */
    public function next()
    {
        $this->cursor++;
        if (!isset($this->data[$this->cursor])) {
            $this->getSubscribers();
        }
    }

    /**
     * Checks if the cursor is valid
     * @return bool
     */
    public function valid()
    {
        return $this->valid && isset($this->data[$this->cursor]);
    }
}
