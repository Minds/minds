<?php

namespace Spec\Minds\Mocks\Cassandra;

class Rows implements \IteratorAggregate, \ArrayAccess
{
    public $_items = [];
    public $_pagingStateToken = '';
    public $_isLastPage = false;

    public function __construct(array $items, $pagingStateToken, $isLastPage = false)
    {
        $this->_items = $items;
        $this->_pagingStateToken = $pagingStateToken;
        $this->_isLastPage = $isLastPage;
    }

    public function getIterator()
    {
        return call_user_func(function () {
            while (list($key, $val) = each($this->_items)) {
                yield $key => $val;
            }
        });
    }

    function pagingStateToken()
    {
        return $this->_pagingStateToken;
    }

    public function offsetExists($offset)
    {
        return isset($this->_items[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->_items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->_items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->_items[$offset]);
    }

    public function isLastPage()
    {
        return $this->_isLastPage;
    }

    public function count()
    {
        return count($this->_items);
    }

    public function current()
    {
        return $this->_items[key($this->_items)];
    }

}
