<?php
namespace Spec\Minds\Mocks\Cassandra;

class Value
{
    protected $_value;

    public function __construct($value)
    {
        $this->_value = $value;
    }

    public function value()
    {
        return $this->_value;
    }
}
