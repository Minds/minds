<?php

/**
 * Minds Feeds Iterator
 *
 * @author emi
 */

namespace Minds\Core\Feeds;

class Iterator implements \Iterator
{
    /** @var Repository */
    protected $repository;

    /** @var int */
    protected $fetchLimit = 200;

    /** @var array */
    protected $data = [];

    /** @var int */
    protected $cursor = -1;

    /** @var bool */
    protected $valid = false;

    /** @var string */
    protected $offset = '';

    /** @var bool */
    protected $manualOffset = false;

    /** @var bool */
    protected $wasIteratingLastPage = false;

    /** @var array */
    protected $repositoryOptions = [];

    /**
     * Iterator constructor.
     * @param null $repository
     */
    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository();
    }

    /**
     * Sets the batch size of every fetch operation
     * @param int $fetchLimit
     * @return Iterator
     */
    public function setFetchLimit($fetchLimit)
    {
        $this->fetchLimit = $fetchLimit;
        return $this;
    }

    /**
     * @param string $offset
     * @return Iterator
     */
    public function setOffset($offset)
    {
        $this->manualOffset = true;
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Sets the repository options to fetch
     * @param array $repositoryOptions
     * @return Iterator
     */
    public function setRepositoryOptions($repositoryOptions)
    {
        $this->repositoryOptions = $repositoryOptions;
        return $this;
    }

    /**
     * Fetches the next set of results from the database
     * @throws \Exception
     */
    public function fetch()
    {
        if (!$this->repositoryOptions) {
            throw new \Exception('Missing iteration repository options');
        }

        if ($this->wasIteratingLastPage) {
            $this->valid = false;
            return;
        }

        $this->data = [];
        $this->cursor = 0;

        $response = $this->repository->getList(array_merge($this->repositoryOptions, [
            'limit' => $this->fetchLimit,
            'offset' => $this->offset,
        ]));

        if (!$response || count($response) === 0 || $response->getException()) {
            $this->valid = false;
            $this->data = [];
            return;
        }

        $this->valid = true;
        $this->data = $response->toArray();
        $this->offset = base64_encode($response->getPagingToken());
        $this->manualOffset = false;
        $this->wasIteratingLastPage = $response->isLastPage();
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->data[$this->cursor];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     * @throws \Exception
     */
    public function next()
    {
        $this->cursor++;

        if (!isset($this->data[$this->cursor])) {
            $this->fetch();
        }
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->valid && isset($this->data[$this->cursor]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     * @throws \Exception
     */
    public function rewind()
    {
        if (!$this->manualOffset) {
            $this->offset = '';
        }
        $this->wasIteratingLastPage = false;

        $this->fetch();
    }
}
