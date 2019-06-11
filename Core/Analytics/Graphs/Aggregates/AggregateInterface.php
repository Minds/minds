<?php
namespace Minds\Core\Analytics\Graphs\Aggregates;

interface AggregateInterface
{
    public function fetch(array $options = []);
}
