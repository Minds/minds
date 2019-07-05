<?php

namespace Spec\Minds\Core\Data\Cassandra;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Data\Cassandra\Scroll;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class ScrollSpec extends ObjectBehavior
{
    /** @var Client */
    protected $db;

    function let(
        Client $db
    )
    {
        $this->beConstructedWith($db);
        $this->db = $db;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Scroll::class);
    }

    function it_should_request_and_scroll()
    {
        $prepared = new Custom();
        $prepared->query('SELECT * FROM phpspec');
        $prepared->setOpts([
            'page_size' => 2,
        ]);

        // 1st iteration

        $this->db->request(Argument::that(function (Custom $prepared) {
            $opts = $prepared->getOpts() ?: [];
            return !isset($opts['paging_state_token']) || !$opts['paging_state_token'];
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([1, 2], '0001'));

        // 2nd iteration

        $this->db->request(Argument::that(function (Custom $prepared) {
            $opts = $prepared->getOpts() ?: [];
            return isset($opts['paging_state_token']) && $opts['paging_state_token'] === '0001';
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([3, 4], '0002'));

        // 3rd iteration

        $this->db->request(Argument::that(function (Custom $prepared) {
            $opts = $prepared->getOpts() ?: [];
            return isset($opts['paging_state_token']) && $opts['paging_state_token'] === '0002';
        }))
            ->shouldBeCalled()
            ->willReturn(new Rows([5], '0003', true));

        //

        $this
            ->request($prepared)
            ->shouldBeAGenerator([1, 2, 3, 4, 5]);
    }

    function getMatchers()
    {
        $matchers = [];

        $matchers['beAGenerator'] = function ($subject, $items) {
            $subjectItems = iterator_to_array($subject);

            if ($subjectItems !== $items) {
                throw new FailureException(sprintf("Subject should be a traversable containing %s, but got %s.", json_encode($items), json_encode($subjectItems)));
            }

            return true;
        };

        return $matchers;
    }
}
