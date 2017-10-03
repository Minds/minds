<?php
namespace Spec\Minds\Mocks\Cassandra;

class Rows implements \IteratorAggregate {
    public $_items = [];
    public $_pagingStateToken = '';

    public function __construct(array $items, $pagingStateToken)
    {
        $this->_items = $items;
        $this->_pagingStateToken = $pagingStateToken;
    }

    public function getIterator()
    {
        return call_user_func(function () {
            while(list($key, $val) = each($this->_items)) {
                yield $key => $val;
            }
        });
    }

    function pagingStateToken()
    {
        return $this->_pagingStateToken;
    }
}
