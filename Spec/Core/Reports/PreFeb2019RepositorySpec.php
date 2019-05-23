<?php

namespace Spec\Minds\Core\Reports;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Exception\Example\FailureException;

use Minds\Core\Data\Cassandra;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;

use Spec\Minds\Mocks;

class PreFeb2019RepositorySpec extends ObjectBehavior
{
    protected $_client;

    function let(Cassandra\Client $client)
    {
        $this->beConstructedWith($client);

        $this->_client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Reports\PreFeb2019Repository');
    }

    // getAll()

    function it_should_return_a_list_of_reports()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'guid' => 5000 ],
            [ 'guid' => 5001 ],
        ], '');

        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll();

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Report::class);
        $return['next']->shouldReturn('');
    }

    function it_should_return_a_list_of_reports_by_owner(
        Entities\User $owner
    )
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'guid' => 5000 ],
            [ 'guid' => 5001 ],
        ], '');

        $owner->get('guid')->willReturn(1000);

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], 'owner_guid = ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll([
            'owner' => $owner
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Report::class);
        $return['next']->shouldReturn('');
    }

    function it_should_return_a_list_of_reports_by_state()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'guid' => 5000 ],
            [ 'guid' => 5001 ],
        ], '');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $template = $query->build();

            return strpos($template['string'], 'state = ?') !== false;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll([
            'state' => 'review'
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Report::class);
        $return['next']->shouldReturn('');
    }

    function it_should_with_an_offset_and_a_limit_return_a_list_of_reports_and_will_return_a_paging_token()
    {
        $rows = new Mocks\Cassandra\Rows([
            [ 'guid' => 5000 ],
            [ 'guid' => 5001 ],
        ], 'phpspec_page_2');

        $this->_client->request(Argument::that(function ($query) {
            if (!($query instanceof Prepared\Custom)) {
                return false;
            }

            $opts = $query->getOpts();

            return
                isset($opts['paging_state_token']) &&
                isset($opts['page_size']) &&
                $opts['paging_state_token'] === 'phpspec_page_1' &&
                $opts['page_size'] === 2
            ;
        }))
            ->shouldBeCalled()
            ->willReturn($rows);

        $return = $this->getAll([
            'limit' => 2,
            'offset' => base64_encode('phpspec_page_1')
        ]);

        $return->shouldHaveKeys(['data', 'next']);

        $return['data']->shouldBeAnArrayOf(2, Entities\Report::class);
        $return['next']->shouldReturn(base64_encode('phpspec_page_2'));
    }

    // getRow()

    function it_should_get_a_single_report()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([
                [ 'guid' => 5000 ]
            ]);

        $this->getRow(5000)
            ->shouldReturnAnInstanceOf(Entities\Report::class);
    }

    function it_should_not_get_a_report_if_no_guid()
    {
        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->getRow(null)
            ->shouldReturn(false);
    }

    function it_should_not_get_a_report_if_doesnt_exist()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->getRow(5404)
            ->shouldReturn(false);
    }

    // create()

    function it_should_create_a_report(
        Entities\Activity $activity,
        Entities\User $reporter
    )
    {
        $activity->get('guid')->willReturn(5000);
        $activity->get('owner_guid')->willReturn(1001);

        $reporter->get('guid')->willReturn(1000);

        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->create($activity, $reporter, 0, '')
            ->shouldReturn(true);
    }

    function it_should_not_create_a_report_if_no_entity(
        Entities\User $reporter
    )
    {
        $reporter->get('guid')->willReturn(1000);

        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->create(null, $reporter, 0, '')
            ->shouldReturn(false);
    }

    function it_should_not_create_a_report_if_no_reporter(
        Entities\Activity $activity
    )
    {
        $activity->get('guid')->willReturn(5000);
        $activity->get('owner_guid')->willReturn(1001);

        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->create($activity, null, 0, '')
            ->shouldReturn(false);
    }

    // update()

    function it_should_update_a_report()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->update(5000, ['test' => 'phpspec'])
            ->shouldReturn(true);
    }

    function it_should_not_update_a_report_if_no_guid()
    {
        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->update(null, ['test' => 'phpspec'])
            ->shouldReturn(false);
    }

    function it_should_not_update_a_report_if_empty_set()
    {
        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->update(5000, [])
            ->shouldReturn(true);
    }

    // delete()

    function it_should_delete_a_report()
    {
        $this->_client->request(Argument::type(Prepared\Custom::class))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete(5000)
            ->shouldReturn(true);
    }

    function it_should_not_delete_a_report_if_no_guid()
    {
        $this->_client->request(Argument::any())
            ->shouldNotBeCalled();

        $this->delete(null)
            ->shouldReturn(false);
    }

    function getMatchers()
    {
        $matchers = [];

        $matchers['haveKeys'] = function($subject, array $keys) {
            $valid = true;

            foreach ($keys as $key) {
                $valid = $valid && array_key_exists($key, $subject);
            }

            return $valid;
        };

        $matchers['beAnArrayOf'] = function ($subject, $count, $class) {
            if (!is_array($subject) || ($count !== null && count($subject) !== $count)) {
                throw new FailureException("Subject should be an array of $count elements");
            }

            $validTypes = true;

            foreach ($subject as $element) {
                if (!($element instanceof $class)) {
                    $validTypes = false;
                    break;
                }
            }

            if (!$validTypes) {
                throw new FailureException("Subject should be an array of {$class}");
            }

            return true;
        };

        return $matchers;
    }
}
