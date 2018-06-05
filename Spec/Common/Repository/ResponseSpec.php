<?php

namespace Spec\Minds\Common\Repository;

use Minds\Common\Repository\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResponseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Common\Repository\Response');
    }

    function it_should_set_and_get_paging_token()
    {
        $this
            ->setPagingToken('phpspec')
            ->shouldReturn($this);

        $this
            ->getPagingToken()
            ->shouldReturn('phpspec');
    }

    function it_should_be_constructed_with_data_and_convert_to_array()
    {
        $this->beConstructedWith([1, 2]);

        $this
            ->toArray()
            ->shouldReturn([1, 2]);
    }

    function it_should_iterate_through_interface()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5]);

        $currentCount = 0;
        foreach ($this->getWrappedObject() as $element) {
            $currentCount++;
            expect($element)->toBe($currentCount);
        }
    }

    function it_should_access_elements_through_interface()
    {
        $this->beConstructedWith(['a', 'b', 'c', 'd']);

        expect($this->getWrappedObject()[0])->toBe('a');
        expect($this->getWrappedObject()[1])->toBe('b');
        expect($this->getWrappedObject()[2])->toBe('c');
        expect($this->getWrappedObject()[3])->toBe('d');
    }

    function it_should_set_elements_through_interface()
    {
        $this->beConstructedWith([]);

        $this->getWrappedObject()[0] = 'a';
        $this->getWrappedObject()[ ] = 'b';
        $this->getWrappedObject()[3] = 'd';

        expect($this->getWrappedObject()[0])->toBe('a');
        expect($this->getWrappedObject()[1])->toBe('b');
        expect($this->getWrappedObject()[3])->toBe('d');
    }

    function it_should_check_set_elements_through_interface()
    {
        $this->beConstructedWith(['a', 'b']);

        expect(isset($this->getWrappedObject()[0]))->toBe(true);
        expect(isset($this->getWrappedObject()[1]))->toBe(true);
        expect(isset($this->getWrappedObject()[2]))->toBe(false);
        expect(isset($this->getWrappedObject()[3]))->toBe(false);

        expect(isset($this->getWrappedObject()[-1]))->toBe(false);
    }

    function it_should_unset_elements_through_interface()
    {
        $this->beConstructedWith(['a', 'b', 'c' , 'd']);

        unset($this->getWrappedObject()[2]);

        expect(isset($this->getWrappedObject()[0]))->toBe(true);
        expect(isset($this->getWrappedObject()[1]))->toBe(true);
        expect(isset($this->getWrappedObject()[2]))->toBe(false);
        expect(isset($this->getWrappedObject()[3]))->toBe(true);
    }

    function it_should_count_through_interface()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5]);

        $this
            ->count()
            ->shouldReturn(5);

        expect(count($this->getWrappedObject()))->toBe(5);
    }

    function it_should_reverse_and_return_a_clone()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5], 'test');

        $methodTest = $this->reverse();

        $methodTest
            ->shouldReturnAnInstanceOf(Response::class);

        $result = $methodTest->getWrappedObject();

        expect($result->toArray())
            ->toBe([ 5, 4, 3, 2, 1 ]);

        expect($result->getPagingToken())->toBe('test');
    }

    function it_should_filter_and_return_a_clone()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5], 'test');

        $methodTest = $this->filter(function ($n) {
            return ($n % 2) !== 0;
        });

        $methodTest
            ->shouldReturnAnInstanceOf(Response::class);

        $result = $methodTest->getWrappedObject();

        expect($result->toArray())
            ->toBe([ 1, 3, 5 ]);

        expect($result->getPagingToken())->toBe('test');
    }

    function it_should_filter_preserving_keys_and_return_a_clone()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5], 'test');

        $methodTest = $this->filter(function ($n) {
            return ($n % 2) !== 0;
        }, true);

        $methodTest
            ->shouldReturnAnInstanceOf(Response::class);

        $result = $methodTest->getWrappedObject();

        expect($result->toArray())
            ->toBe([
                0 => 1,
                2 => 3,
                4 => 5,
            ]);

        expect($result->getPagingToken())->toBe('test');
    }

    function it_should_map_and_return_a_clone()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5], 'test');

        $methodTest = $this->map(function ($n) {
            return $n * 2;
        });

        $methodTest
            ->shouldReturnAnInstanceOf(Response::class);

        $result = $methodTest->getWrappedObject();

        expect($result->toArray())
            ->toBe([ 2, 4, 6, 8, 10 ]);

        expect($result->getPagingToken())->toBe('test');
    }

    function it_should_reduce_and_return_the_final_value()
    {
        $this->beConstructedWith([1, 2, 3, 4, 5]);

        $this
            ->reduce(function ($carry, $value) {
                return $carry + $value;
            }, 10)
            ->shouldReturn(25);
    }
}
