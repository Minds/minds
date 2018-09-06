<?php

namespace Spec\Minds\Core\Blockchain\Services;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Config;
use Minds\Core\Blockchain\Services\EtherscanTransactionsByDate;

use Minds\Core\Blockchain\Services\Etherscan;

class EtherscanTransactionsByDateSpec extends ObjectBehavior
{
    private $address;
    // estimationBoundary
    private $_eb = 1500;
    private $_eb2;

    function let(Etherscan $service, Config $config)
    {
        $config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn([
                'etherscan' => [
                    'average_block_time' => 14.7
                ]
            ]);
            $this->beConstructedWith($service, $config);
            $this->_eb2 = 2 * $this->_eb;
    }

    private function _page($number, $page = 0)
    {
        return [
            ($number + ($this->_eb2 * $page)) - $this->_eb,
            ($number + ($this->_eb2 * $page)) + $this->_eb
        ];
    }

    function it_is_initializable(Etherscan $service)
    {
        $this->shouldHaveType(EtherscanTransactionsByDate::class);
    }

    function it_should_set_the_address(Etherscan $service)
    {
        $service->setAddress('0x0101010101')->shouldBeCalled();
        $this->setAddress('0x0101010101')->shouldReturn($this);
    }

    function it_should_estimate_the_blocknumber_for_a_timestamp(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);
    }

    function it_should_check_if_a_timestamp_is_contained_in_an_array_of_blocks(Etherscan $service)
    {
        $fakeData = [
            ['timeStamp' => 110],
            ['timeStamp' => 100],
            ['timeStamp' => 90],
            ['timeStamp' => 80],
            ['timeStamp' => 70],
            ['timeStamp' => 60],
            ['timeStamp' => 50],
            ['timeStamp' => 40],
            ['timeStamp' => 30],
            ['timeStamp' => 20],
            ['timeStamp' => 10],
        ];

        // is contained
        $this->isInRange($fakeData, 65)->shouldReturn(0);
        // timestamp is > that the range covered by data
        $this->isInRange($fakeData, 120)->shouldReturn(1);
        // timestamp is < that the range covered by data
        $this->isInRange($fakeData, 8)->shouldReturn(-1);
    }

    function it_should_search_the_nearest_block_to_a_timestamp(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData = [
            ['timeStamp' => $timestamp + 70],
            ['timeStamp' => $timestamp + 60],
            ['timeStamp' => $timestamp + 50],
            ['timeStamp' => $timestamp + 40],
            ['timeStamp' => $timestamp + 30],
            ['timeStamp' => $timestamp + 20],
            ['timeStamp' => $timestamp + 10],
            ['timeStamp' => $timestamp],  // searched block
            ['timeStamp' => $timestamp - 10],
            ['timeStamp' => $timestamp - 20],
            ['timeStamp' => $timestamp - 30]
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData);

        // will truncate unneeded data
        $return = array_slice($fakeData, 0, 8);
        // is contained
        $this->searchBegining($timestamp)->shouldReturn($return);
    }

    function it_should_search_on_the_previous_chunk_if_not_found(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData1 = [
            ['timeStamp' => $timestamp + 140],
            ['timeStamp' => $timestamp + 130],
            ['timeStamp' => $timestamp + 120],
            ['timeStamp' => $timestamp + 110],
            ['timeStamp' => $timestamp + 100],
            ['timeStamp' => $timestamp + 90],
            ['timeStamp' => $timestamp + 80],
        ];

        $fakeData2 = [
            ['timeStamp' => $timestamp + 70],
            ['timeStamp' => $timestamp + 60],
            ['timeStamp' => $timestamp + 50],
            ['timeStamp' => $timestamp + 40],
            ['timeStamp' => $timestamp + 30],
            ['timeStamp' => $timestamp + 20],
            ['timeStamp' => $timestamp + 10],
            ['timeStamp' => $timestamp],  // searched block
            ['timeStamp' => $timestamp - 10],
            ['timeStamp' => $timestamp - 20],
            ['timeStamp' => $timestamp - 30]
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData1);
        // fetch older data
        list($from, $to) = $this->_page(649510, -1);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData2);

        // will truncate unneeded data
        $return = array_slice($fakeData2, 0, 8);
        // is contained
        $this->searchBegining($timestamp)->shouldReturn($return);
    }

    function it_should_search_on_the_next_chunk_if_not_found(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData1 = [
            ['timeStamp' => $timestamp - 80],
            ['timeStamp' => $timestamp - 90],
            ['timeStamp' => $timestamp - 100],
            ['timeStamp' => $timestamp - 110],
            ['timeStamp' => $timestamp - 120],
            ['timeStamp' => $timestamp - 130],
            ['timeStamp' => $timestamp - 140],
        ];

        $fakeData2 = [
            ['timeStamp' => $timestamp + 70],
            ['timeStamp' => $timestamp + 60],
            ['timeStamp' => $timestamp + 50],
            ['timeStamp' => $timestamp + 40],
            ['timeStamp' => $timestamp + 30],
            ['timeStamp' => $timestamp + 20],
            ['timeStamp' => $timestamp + 10],
            ['timeStamp' => $timestamp], // searched block
            ['timeStamp' => $timestamp - 10],
            ['timeStamp' => $timestamp - 20],
            ['timeStamp' => $timestamp - 30]
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData1);
        // fetch newer data
        list($from, $to) = $this->_page(649510, 1);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData2);

        // will truncate unneeded data
        $return = array_slice($fakeData2, 0, 8);
        // is contained
        $this->searchBegining($timestamp)->shouldReturn($return);
    }

    function it_should_detect_if_timestamp_is_on_the_limit_of_data(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData1 = [
            ['timeStamp' => $timestamp + 70],
            ['timeStamp' => $timestamp + 60],
            ['timeStamp' => $timestamp + 50],
            ['timeStamp' => $timestamp + 40],
            ['timeStamp' => $timestamp + 30],
            ['timeStamp' => $timestamp + 20],
            ['timeStamp' => $timestamp + 10],
            ['timeStamp' => $timestamp + 5], // searched block
        ];

        $fakeData2 = [
            ['timeStamp' => $timestamp - 10],
            ['timeStamp' => $timestamp - 20],
            ['timeStamp' => $timestamp - 30],
            ['timeStamp' => $timestamp - 40],
            ['timeStamp' => $timestamp - 50]
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData1);
        // fetch older data
        list($from, $to) = $this->_page(649510, -1);
        $service->getTransactions( $from, $to)->shouldBeCalled()->willReturn($fakeData2);

        $this->searchBegining($timestamp)->shouldReturn($fakeData1);
    }

    function it_should_detect_if_timestamp_is_on_the_limit_of_new_data(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData0 = [
            ['timeStamp' => $timestamp - 55],
            ['timeStamp' => $timestamp - 60],
            ['timeStamp' => $timestamp - 80],
            ['timeStamp' => $timestamp - 90],
            ['timeStamp' => $timestamp - 100]
        ];

        $fakeData1 = [
            ['timeStamp' => $timestamp - 10],
            ['timeStamp' => $timestamp - 20],
            ['timeStamp' => $timestamp - 30],
            ['timeStamp' => $timestamp - 40],
            ['timeStamp' => $timestamp - 50]
        ];

        $fakeData2 = [
            ['timeStamp' => $timestamp + 70],
            ['timeStamp' => $timestamp + 60],
            ['timeStamp' => $timestamp + 50],
            ['timeStamp' => $timestamp + 40],
            ['timeStamp' => $timestamp + 30],
            ['timeStamp' => $timestamp + 20],
            ['timeStamp' => $timestamp + 10],
            ['timeStamp' => $timestamp + 5], // searched block
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData0);
        list($from, $to) = $this->_page(649510, 1);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData1);
        list($from, $to) = $this->_page(649510, 2);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData2);

        $this->searchBegining($timestamp)->shouldReturn($fakeData2);
    }

    function it_should_throw_error_if_search_fail_after_three_fetchs(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);


        $fakeData0 = [
            ['timeStamp' => $timestamp - 55],
        ];

        $fakeData1 = [
            ['timeStamp' => $timestamp - 10],
        ];

        $fakeData2 = [
            ['timeStamp' => $timestamp - 5],
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData0);
        list($from, $to) = $this->_page(649510, 1);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData1);
        list($from, $to) = $this->_page(649510, 2);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData2);


        // is contained
        $this->shouldThrow('\Exception')->during('searchBegining', [$timestamp]);
    }

    function it_should_return_blocks_for_a_date_range(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $toTimestamp = time();
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData0 = [
            ['timeStamp' => $timestamp + 30, 'blockNumber' => 649513],
            ['timeStamp' => $timestamp + 20, 'blockNumber' => 649512],
            ['timeStamp' => $timestamp + 10, 'blockNumber' => 649511],
            ['timeStamp' => $timestamp, 'blockNumber' => 649510],  // begin block
            ['timeStamp' => $timestamp - 10, 'blockNumber' => 649509],
            ['timeStamp' => $timestamp - 20, 'blockNumber' => 649508],
            ['timeStamp' => $timestamp - 30]
        ];

        $fakeData1 = [
            ['timeStamp' => $timestamp + 80, 'blockNumber' => 657518],
            ['timeStamp' => $timestamp + 70, 'blockNumber' => 657517],
            ['timeStamp' => $timestamp + 60, 'blockNumber' => 657516],
            ['timeStamp' => $timestamp + 50, 'blockNumber' => 657515],
            ['timeStamp' => $timestamp + 40, 'blockNumber' => 657514],
        ];

        $fakeData2 = [
            ['timeStamp' => $toTimestamp + 80, 'blockNumber' => 649522],
            ['timeStamp' => $toTimestamp, 'blockNumber' => 649521],  // finish block
            ['timeStamp' => $timestamp + 110, 'blockNumber' => 649520],
            ['timeStamp' => $timestamp + 100, 'blockNumber' => 649519],
            ['timeStamp' => $timestamp + 90, 'blockNumber' => 649518],
        ];

        // return all pages with data out of date range truncated
        $result = [
            ['timeStamp' => $toTimestamp, 'blockNumber' => 649521],
            ['timeStamp' => $timestamp + 110, 'blockNumber' => 649520],
            ['timeStamp' => $timestamp + 100, 'blockNumber' => 649519],
            ['timeStamp' => $timestamp + 90, 'blockNumber' => 649518],
            ['timeStamp' => $timestamp + 80, 'blockNumber' => 657518],
            ['timeStamp' => $timestamp + 70, 'blockNumber' => 657517],
            ['timeStamp' => $timestamp + 60, 'blockNumber' => 657516],
            ['timeStamp' => $timestamp + 50, 'blockNumber' => 657515],
            ['timeStamp' => $timestamp + 40, 'blockNumber' => 657514],
            ['timeStamp' => $timestamp + 30, 'blockNumber' => 649513],
            ['timeStamp' => $timestamp + 20, 'blockNumber' => 649512],
            ['timeStamp' => $timestamp + 10, 'blockNumber' => 649511],
            ['timeStamp' => $timestamp, 'blockNumber' => 649510],
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData0);
        $service->getTransactions(649514, 657514)->shouldBeCalled()->willReturn($fakeData1);
        $service->getTransactions(657515, 665515)->shouldBeCalled()->willReturn($fakeData2);

        $this->getRange($timestamp, $toTimestamp)->shouldReturn($result);
    }

    function it_should_truncate_the_last_block_if_it_out_date_rage(Etherscan $service)
    {
        $timestamp = time() - (60 * 60 * 2);
        $toTimestamp = time();
        $service->getLastBlockNumber()->willReturn(650000);
        $this->estimateBlock($timestamp)->shouldReturn((double) 649510);

        $fakeData0 = [
            ['timeStamp' => $toTimestamp + 80, 'blockNumber' => 649522],
            ['timeStamp' => $toTimestamp + 5, 'blockNumber' => 649521],  // finish block
            ['timeStamp' => $timestamp + 110, 'blockNumber' => 649520],
            ['timeStamp' => $timestamp + 100, 'blockNumber' => 649519],
            ['timeStamp' => $timestamp + 90, 'blockNumber' => 649518],
            ['timeStamp' => $timestamp + 30, 'blockNumber' => 649513],
            ['timeStamp' => $timestamp + 20, 'blockNumber' => 649512],
            ['timeStamp' => $timestamp + 10, 'blockNumber' => 649511],
            ['timeStamp' => $timestamp, 'blockNumber' => 649510],  // begin block
            ['timeStamp' => $timestamp - 10, 'blockNumber' => 649509],
            ['timeStamp' => $timestamp - 20, 'blockNumber' => 649508],
            ['timeStamp' => $timestamp - 30]
        ];

        // return all pages with data out of date range truncated
        $result = [
            ['timeStamp' => $timestamp + 110, 'blockNumber' => 649520],
            ['timeStamp' => $timestamp + 100, 'blockNumber' => 649519],
            ['timeStamp' => $timestamp + 90, 'blockNumber' => 649518],
            ['timeStamp' => $timestamp + 30, 'blockNumber' => 649513],
            ['timeStamp' => $timestamp + 20, 'blockNumber' => 649512],
            ['timeStamp' => $timestamp + 10, 'blockNumber' => 649511],
            ['timeStamp' => $timestamp, 'blockNumber' => 649510],
        ];

        list($from, $to) = $this->_page(649510);
        $service->getTransactions($from, $to)->shouldBeCalled()->willReturn($fakeData0);

        $this->getRange($timestamp, $toTimestamp)->shouldReturn($result);
    }
}