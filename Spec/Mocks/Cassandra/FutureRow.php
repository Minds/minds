<?php
namespace Spec\Minds\Mocks\Cassandra;

class FutureRow {
    protected $val;

    public function __construct($val)
    {
        $this->val = $val;
    }

    public function get()
    {
        return $this->val;
    }
}
