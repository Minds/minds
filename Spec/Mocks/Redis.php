<?php

namespace Spec\Mocks;

/**
 * Redis.
 *
 * @author emi
 */
class Redis
{
    public function get(...$args) {}
    public function set(...$args) {}
    public function del(...$args) {}
    public function expire(...$args) {}
    public function zAdd(...$args) {}
    public function zRange(...$args) {}
}
