<?php

namespace Spec\Minds\Core\Search;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DocumentsSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Search\Documents');
    }

    function it_should_format_a_user_document()
    {
        $user = [
          'guid' => 'abc',
          'type' => 'user',
          'username' => 'mark',
          'name' => 'MarkEdward Harding',
          'featured_id' => null,
          'admin' => null
        ];
        $this->formatDocumentBody($user)->shouldReturn([
          'guid' => 'abc',
          'type' => 'user',
          'username' => 'mark',
          'name' => 'MarkEdward Harding',
          'featured_id' => null,
          'admin' => null,
          'suggest' => [
            'input' => [
              'mark',
              'MarkEdward Harding',
              'Mark Edward Harding',
              'Mark Harding Edward',
              'Edward Mark Harding',
              'Edward Harding Mark',
              'Harding Mark Edward',
              'Harding Edward Mark'
            ],
            'output' => "@mark",
            'weight' => 1,
            'payload' => [
              'guid' => 'abc',
              'name' => 'MarkEdward Harding',
              'username' => 'mark'
            ]
          ]
        ]);
    }
}
