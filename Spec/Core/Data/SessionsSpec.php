<?php

namespace Spec\Minds\Core\Data;

use Minds\Core\Data\cache\Redis;
use Minds\Core\Data\Call;
use Minds\Core\Data\Sessions;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SessionsSpec extends ObjectBehavior
{
    /** @var Call */
    protected $call;
    /** @var Redis */
    protected $redis;

    function let(Call $call, Redis $redis, User $user)
    {
        $this->beConstructedWith($call, $redis);

        $this->call = $call;
        $this->redis = $redis;

        $user->get('guid')
        ->willReturn('123');

        $_SESSION['user'] = $user;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Sessions::class);
    }

    function it_should_read_from_cache()
    {
        $this->redis->get('1234')
            ->shouldBeCalled()
            ->willReturn('test');

        $this->read('1234')->shouldReturn('test');
    }


    function it_should_read_from_database()
    {
        $this->redis->get('1234')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->call->getRow('1234')
            ->shouldBeCalled()
            ->willReturn(['data' => base64_encode('test')]);

        $this->read('1234')->shouldReturn('test');
    }

    function it_should_write()
    {
        $this->redis->set('1234', 'test', Argument::any())
            ->shouldBeCalled()
            ->willReturn(null);

        $this->call->insert('1234', Argument::that(function ($param) {
            return isset($param['ts']) && $param['data'] === base64_encode('test');
        }), Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->call->insert(Argument::containingString('user:123'), Argument::that(function ($param) {
            return isset($param['1234']);
        }), Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->write('1234', 'test');
    }

    function it_should_destroy()
    {
        $this->redis->destroy('1234')
            ->shouldBeCalled();

        $this->call->removeRow('1234')
            ->shouldBeCalled();

        $this->call->removeAttributes(Argument::containingString('user:123'), ['1234'])
            ->shouldBeCalled();

        $this->destroy('1234');
    }

    function it_should_destroy_all()
    {
        $this->call->getRow('user:123', ['limit' => 99999, 'reversed' => false])
            ->shouldBeCalled()
            ->willReturn(
                [
                    '1234' => time(),
                    '5678' => time()
                ]
            );

        $this->redis->destroy('1234')
            ->shouldBeCalled();

        $this->redis->destroy('5678')
            ->shouldBeCalled();


        $this->call->removeRow('user:123')
            ->shouldBeCalled();


        $this->destroyAll('123')->shouldReturn(true);
    }

    function it_should_sync_all()
    {
        $this->syncAll('123')->shouldReturn(true);
    }

    function it_should_return_session_count()
    {
        $this->call->countRow('user:123', Argument::any())
            ->shouldBeCalled()
            ->willReturn(30);


        $this->count('123')->shouldReturn(30);
    }
}
