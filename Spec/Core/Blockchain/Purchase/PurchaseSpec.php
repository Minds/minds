<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use Minds\Core\Blockchain\Purchase\Purchase;
use Minds\Core\Data\Call;
use Minds\Core\Data\lookup;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use PhpSpec\ObjectBehavior;

class PurchaseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Purchase::class);
    }

    function it_should_get_unissued_amount()
    {
        $this->getUnissuedAmount()->shouldReturnAnInstanceOf(BigNumber::class);
    }

    function it_should_export(Call $call, lookup $lookup)
    {
        Di::_()->bind('Database\Cassandra\Indexes', function ($di) use ($call) {
            return $call->getWrappedObject();
        });

        Di::_()->bind('Database\Cassandra\Data\Lookup', function ($di) use ($lookup) {
            return $lookup->getWrappedObject();
        });

        $this->setUserGuid('123');
        $this->setWalletAddress('0x123');
        $this->setTx('0x123123');
        $this->setRequestedAmount(300);
        $this->setIssuedAmount(20);
        $this->setStatus('failed');
        $this->setTimestamp(12345678);

        $this->setPhoneNumberHash('hash');

        $this->jsonSerialize()->shouldHaveKeyWithValue('user_guid', '123');
        $this->jsonSerialize()->shouldHaveKeyWithValue('wallet_address', '0x123');
        $this->jsonSerialize()->shouldHaveKeyWithValue('tx', '0x123123');
        $this->jsonSerialize()->shouldHaveKeyWithValue('requested_amount', 300);
        $this->jsonSerialize()->shouldHaveKeyWithValue('issued_amount', 20);
        $this->jsonSerialize()->shouldHaveKeyWithValue('status', 'failed');
        $this->jsonSerialize()->shouldHaveKeyWithValue('timestamp', 12345678000);
        $this->jsonSerialize()['user']->shouldBeArray();
    }

    function it_should_perform_a_full_export(Call $call, lookup $lookup)
    {
        Di::_()->bind('Database\Cassandra\Indexes', function ($di) use ($call) {
            return $call->getWrappedObject();
        });

        Di::_()->bind('Database\Cassandra\Data\Lookup', function ($di) use ($lookup) {
            return $lookup->getWrappedObject();
        });

        $this->setUserGuid('123');
        $this->setWalletAddress('0x123');
        $this->setTx('0x123123');
        $this->setRequestedAmount(300);
        $this->setIssuedAmount(20);
        $this->setStatus('failed');
        $this->setTimestamp(12345678);

        $this->setPhoneNumberHash('hash');

        $this->export(true)->shouldHaveKeyWithValue('user_guid', '123');
        $this->export(true)->shouldHaveKeyWithValue('wallet_address', '0x123');
        $this->export(true)->shouldHaveKeyWithValue('tx', '0x123123');
        $this->export(true)->shouldHaveKeyWithValue('requested_amount', 300);
        $this->export(true)->shouldHaveKeyWithValue('issued_amount', 20);
        $this->export(true)->shouldHaveKeyWithValue('status', 'failed');
        $this->export(true)->shouldHaveKeyWithValue('timestamp', 12345678000);
        $this->export(true)->shouldHaveKeyWithValue('phone_number_hash', 'hash');
        $this->export(true)['user']->shouldBeArray();
    }
}
