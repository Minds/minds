<?php

namespace Spec\Minds\Core\Seo;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Seo\Manager');
    }

    public function it_should_return_metadata_on_route()
    {
        $this::add('phpspec/test', function ($slugs) {
        return array('foo' => 'bar');
      });
        $this::get('phpspec/test')->shouldHaveKeyWithValue('foo', 'bar');
    }

    public function it_should_provide_slugs_to_callbacks()
    {
        $this::add('phpspec/test', function ($slugs) {
        return array('slugs' => $slugs);
      });
        $this::get('phpspec/test/slug1/slug2')->shouldContain(array('slug1', 'slug2'));
    }
}
