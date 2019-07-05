<?php

namespace Spec\Minds\Core\Channels\Snapshots;

use Minds\Core\Channels\Snapshots\Snapshot;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SnapshotSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Snapshot::class);
    }

    public function it_should_get_single_key()
    {
        $this
            ->setKey('test')
            ->getKey()
            ->shouldReturn('test');
    }

    public function it_should_get_multi_key()
    {
        $this
            ->setKey(['test', 'phpspec'])
            ->getKey()
            ->shouldReturn("test\tphpspec");
    }

    function it_should_set_and_get_json_data()
    {
        $jsonData = [ 'foo' => 'bar', 'baz' => 1 ];
        $this
            ->setJsonData($jsonData)
            ->getJsonData()
            ->shouldReturn($jsonData);
    }

    function it_should_set_and_get_raw_json_data()
    {
        $jsonData = '{"foo":"bar","baz":1}';
        $this
            ->setJsonData($jsonData)
            ->getJsonData(true)
            ->shouldReturn($jsonData);
    }
}
