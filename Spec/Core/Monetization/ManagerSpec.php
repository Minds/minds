<?php

namespace Spec\Minds\Core\Monetization;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data;
use Minds\Core\Data\Cassandra;

class ManagerSpec extends ObjectBehavior
{
    private $_db;
    private $_timeline;

    // Mock rows
    private static $_ROW_1 = [ 'guid' => 1, 'ts' => 1010, 'type' => 'credit', 'user_guid' => 10, 'status' => 'paid' ];
    private static $_ROW_2 = [ 'guid' => 2, 'ts' => 1020, 'type' => 'credit', 'user_guid' => 11, 'status' => 'paid' ];
    private static $_ROW_3 = [ 'guid' => 3, 'ts' => 1030, 'type' => 'credit', 'user_guid' => 10, 'status' => 'paid' ];

    public function let(Cassandra\Client $db, Data\Call $timeline)
    {
        $this->beConstructedWith($db, $timeline);

        $this->_db = $db;
        $this->_timeline = $timeline;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Monetization\Manager');
    }

    function it_should_get_admin_rows_with_order_reversed()
    {
        $this->_timeline->getRow('monetization_ledger:admin', [
            'limit' => 3,
            'reversed' => true,
        ])
            ->shouldBeCalled()
            ->willReturn([ '3' => '3', '2' => '2', '1' => '1' ]);

        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '3' &&
                $build['values'][1] == '2' &&
                $build['values'][2] == '1'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
                $this::$_ROW_2,
                $this::$_ROW_3,
            ]);

        $this->get([
            'limit' => 3,
            'order' => 'DESC'
        ])->shouldReturn([
            $this::$_ROW_3,
            $this::$_ROW_2,
            $this::$_ROW_1,
        ]);
    }

    function it_should_get_admin_rows_with_order_reversed_and_offset()
    {
        $this->_timeline->getRow('monetization_ledger:admin', [
            'limit' => 3,
            'reversed' => true,
            'offset' => '2'
        ])
            ->shouldBeCalled()
            ->willReturn([ '2' => '2', '1' => '1' ]);

        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return $build['values'][0] == '1';
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
            ]);

        $this->get([
            'limit' => 3,
            'order' => 'DESC',
            'offset' => '2'
        ])->shouldReturn([
            $this::$_ROW_1,
        ]);
    }

    function it_should_get_user_rows()
    {
        $this->_timeline->getRow('monetization_ledger:user:10', [
            'limit' => 2,
            'reversed' => false,
        ])
            ->shouldBeCalled()
            ->willReturn([ '1' => '1', '3' => '3' ]);

        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '1' &&
                $build['values'][1] == '3'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
                $this::$_ROW_3,
            ]);

        $this->get([
            'limit' => 2,
            'user_guid' => '10',
        ])->shouldReturn([
            $this::$_ROW_1,
            $this::$_ROW_3,
        ]);
    }

    function it_should_get_user_rows_with_type()
    {
        $this->_timeline->getRow('monetization_ledger:user:10:credit', [
            'limit' => 2,
            'reversed' => false,
        ])
            ->shouldBeCalled()
            ->willReturn([ '1' => '1', '3' => '3' ]);

        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '1' &&
                $build['values'][1] == '3'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
                $this::$_ROW_3,
            ]);

        $this->get([
            'limit' => 2,
            'user_guid' => '10',
            'type' => 'credit',
        ])->shouldReturn([
            $this::$_ROW_1,
            $this::$_ROW_3,
        ]);
    }

    function it_should_get_user_rows_with_type_and_status()
    {
        $this->_timeline->getRow('monetization_ledger:user:10:credit:paid', [
            'limit' => 2,
            'reversed' => false,
        ])
            ->shouldBeCalled()
            ->willReturn([ '1' => '1', '3' => '3' ]);

        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '1' &&
                $build['values'][1] == '3'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
                $this::$_ROW_3,
            ]);

        $this->get([
            'limit' => 2,
            'user_guid' => '10',
            'type' => 'credit',
            'status' => 'paid',
        ])->shouldReturn([
            $this::$_ROW_1,
            $this::$_ROW_3,
        ]);
    }

    function it_should_fetch_rows()
    {
        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '1' &&
                $build['values'][1] == '3'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
                $this::$_ROW_3,
            ]);

        $this->fetch(['1', '3'])
            ->shouldReturn([
                $this::$_ROW_1,
                $this::$_ROW_3,
            ]);
    }

    function it_should_not_fetch_non_existant_rows()
    {
        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '4'
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([ ]);

        $this->fetch(['4'])->shouldReturn([]);
    }

    function it_should_not_fetch_empty_guid_array()
    {
        $this->_db->request(Argument::any())
            ->shouldNotBeCalled();

        $this->fetch([])->shouldReturn([]);
    }

    function it_should_resolve_a_guid()
    {
        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '1' 
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
                $this::$_ROW_1,
            ]);

        $this->resolve('1')->shouldReturn($this::$_ROW_1);
    }

    function it_should_not_resolve_a_non_existing_guid()
    {
        $this->_db->request(Argument::that(function ($prepared) {
            $build = $prepared->build();

            return
                $build['values'][0] == '4' 
            ;
        }))
            ->shouldBeCalled()
            ->willReturn([
            ]);

        $this->resolve('4')->shouldReturn(false);
    }

    function it_should_not_resolve_an_empty_guid()
    {
        $this->_db->request(Argument::any())
            ->shouldNotBeCalled();

        $this->resolve('')->shouldReturn(false);
    }

    function it_should_insert_a_row()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->_timeline->insert(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldBeCalledTimes(6)
            ->willReturn(true);

        $this->insert($this::$_ROW_1)
            ->shouldReturn(true);
    }

    function it_should_not_insert_a_row_if_required_field_is_missing()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldNotBeCalled();

        $this->_timeline->insert(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringInsert([ 'ts' => 1010, 'type' => 'credit', 'user_guid' => 10, 'status' => 'paid' ]);

        $this->shouldThrow(\Exception::class)
            ->duringInsert([ 'guid' => 1, 'ts' => 1010, 'user_guid' => 10, 'status' => 'paid' ]);

        $this->shouldThrow(\Exception::class)
            ->duringInsert([ 'guid' => 1, 'ts' => 1010, 'type' => 'credit', 'status' => 'paid' ]);

        $this->shouldThrow(\Exception::class)
            ->duringInsert([ 'guid' => 1, 'ts' => 1010, 'type' => 'credit', 'user_guid' => 10 ]);
    }

    function it_should_update_a_row_without_index_modifications()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->update('1', [ 'ts' => 1050 ])
            ->shouldReturn(true);
    }

    function it_should_update_a_row_with_index_modifications()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldBeCalled()
            ->willReturn(true);

        $this->_timeline->insert(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this->_timeline->removeAttributes(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldBeCalledTimes(2)
            ->willReturn(true);

        $this->update(
            '1',
            [ 'status' => 'paid' ],
            [ 'guid' => 1, 'ts' => 1010, 'type' => 'credit', 'user_guid' => 10, 'status' => 'inprogress' ]
        )
            ->shouldReturn(true);
    }

    function it_should_not_update_a_row_with_key_modifications()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldNotBeCalled();

        $this->_timeline->insert(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldNotBeCalled();

        $this->_timeline->removeAttributes(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringUpdate('1', [ 'guid' => '2' ]);

        $this->shouldThrow(\Exception::class)
            ->duringUpdate('1', [ 'type' => 'debit' ]);

        $this->shouldThrow(\Exception::class)
            ->duringUpdate('1', [ 'user_guid' => '11' ]);
    }

    function it_should_not_update_a_row_with_index_modifications_without_old_data()
    {
        $this->_db->request(Argument::type(Cassandra\Prepared\MonetizationLedger::class), true)
            ->shouldNotBeCalled();

        $this->_timeline->insert(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldNotBeCalled();

        $this->_timeline->removeAttributes(Argument::containingString('monetization_ledger:'), Argument::type('array'))
            ->shouldNotBeCalled();

        $this->shouldThrow(\Exception::class)
            ->duringUpdate('1', [ 'status' => 'paid' ]);

        $this->shouldThrow(\Exception::class)
            ->duringUpdate('1', [ 'status' => 'paid' ], []);
    }
}
