<?php

namespace Spec\Minds\Core\Navigation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Navigation\Item;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Client as CassandraClient;

class ManagerSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Navigation\Manager');
    }

    public function it_should_add_an_item_to_a_container(Item $item)
    {
        $this::add($item, "phpspec");
    }

    public function it_should_export_items_for_a_container(Item $item)
    {
        $this::add($item, "phpspec");
        $this::export("phpspec")->shouldHaveKey("phpspec");
    }
}
