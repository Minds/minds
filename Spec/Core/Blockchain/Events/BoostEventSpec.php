<?php

namespace Spec\Minds\Core\Blockchain\Events;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Transactions\Transaction;
use Minds\Core\Blockchain\Transactions\Repository as TXRepository;
use Minds\Core\Boost\Repository as BoostRepository;
use Minds\Entities\Boost\Network as Boost;
use Minds\Core\Data\MongoDB\Client as Mongo;

class BoostEventSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Events\BoostEvent');
    }

    function it_should_record_as_failed(
        Mongo $mongo,
        TXRepository $txRepository,
        BoostRepository $boostRepository,
        Boost $boost
    )
    {
        $this->beConstructedWith($mongo, $txRepository, $boostRepository);

        $boost->getState()
            ->willReturn('pending');

        $boost->getId()
            ->willReturn('boostID');

        $boost->setState('failed')
            ->shouldBeCalled()
            ->willReturn($boost);

        $boost->save()
            ->shouldBeCalled()
            ->willReturn(true);

        $boostRepository->getEntity(null, null)
            ->shouldBeCalled()
            ->willReturn($boost);


        $transaction = new Transaction();
        $transaction->setTx('testTX')
            ->setContract('boost')
            ->setFailed(false);

        $txRepository->update($transaction, [ 'failed' ])
            ->shouldBeCalled();

        $mongo->remove('boost', [ '_id' => 'boostID' ])
            ->shouldBeCalled();

        $this->boostFail(null, $transaction);
    }

}
