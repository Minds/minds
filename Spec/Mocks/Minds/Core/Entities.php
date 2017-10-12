<?php
namespace Spec\Minds\Mocks\Minds\Core;

class Entities
{
    public function get(array $options = []) { }
    public function view($options) { }
    public function build($row, $cache = true) { }
    public function buildNamespace(array $options) { }
}
