<?php

/**
 * Minds Repository Response
 *
 * @author emi
 */

namespace Minds\Common\Repository;

use Exception;

class Response implements \Iterator, \ArrayAccess, \Countable, \JsonSerializable
{
    /** @var array */
    protected $data = [];

    /** @var string */
    protected $pagingToken;

    /** @var Exception */
    protected $exception;

    /** @var bool */
    protected $lastPage = false;

    public function __construct(array $data = null, $pagingToken = null)
    {
        if ($data !== null) {
            $this->data = $data;
        }

        if ($pagingToken !== null) {
            $this->pagingToken = $pagingToken;
        }
    }

    /**
     * Sets the paging token for this result set
     * @param string $pagingToken
     * @return Response
     */
    public function setPagingToken($pagingToken)
    {
        $this->pagingToken = $pagingToken;
        return $this;
    }

    /**
     * Gets the paging token for this result set
     * @return string
     */
    public function getPagingToken()
    {
        return $this->pagingToken;
    }

    /**
     * Sets the exception for a faulty result set
     * @param Exception $exception
     * @return Response
     */
    public function setException($exception)
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * Gets the exception for a faulty result set
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * Sets the flag for a last page of a response
     * @param bool $lastPage
     * @return Response
     */
    public function setLastPage($lastPage)
    {
        $this->lastPage = $lastPage;
        return $this;
    }

    /**
     * Returns if it's the last page of a response
     * @return bool
     */
    public function isLastPage()
    {
        return !!$this->lastPage;
    }

    /**
     * Returns if the result set is fauly
     * @return bool
     */
    public function hasFailed()
    {
        return !!$this->exception;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        next($this->data);
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return key($this->data);
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
        return key($this->data) !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Rewinds the Iterator to the first element and returns its value
     * @return mixed
     */
    public function reset()
    {
        return reset($this->data);
    }

    /**
     * Sets the pointer onto the last Iterator element and returns its value
     * @return mixed
     */
    public function end()
    {
        return end($this->data);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->data[] = $value;
            return;
        }

        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Exports the data array
     * @return array
     */
    public function toArray()
    {
        return $this->data ?: [];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Returns a clone of this response with the inverse order
     * @return Response
     */
    public function reverse($preserveKeys = false)
    {
        return new static(array_reverse($this->data, $preserveKeys), $this->pagingToken);
    }

    /**
     * Iterates over each element passing them to the callback function.
     * If the callback function returns true, the element is returned into
     * the result Response.
     * @param callable $callback
     * @param bool $preserveKeys
     * @return Response
     */
    public function filter($callback, $preserveKeys = false)
    {
        $filtered = array_filter($this->data, $callback, ARRAY_FILTER_USE_BOTH);

        if (!$preserveKeys) {
            $filtered = array_values($filtered);
        }

        return new static($filtered, $this->pagingToken);
    }

    /**
     * Applies the callback to the elements and returns a clone of the Response
     * @param callable $callback
     * @return Response
     */
    public function map($callback)
    {
        return new static(array_map($callback, $this->data), $this->pagingToken);
    }

    /**
     * Iteratively reduce the Response to a single value using a callback function
     * @param callable $callback
     * @param mixed $initialValue
     * @return mixed
     */
    public function reduce($callback, $initialValue = null)
    {
        return array_reduce($this->data, $callback, $initialValue);
    }
}
