<?php

namespace Spec\Minds\Core\Rewards;

use Minds\Core\Config;
use Minds\Core\Data\ElasticSearch\Client;
use Minds\Core\Di\Di;
use Minds\Core\Rewards\ReferralValidator;
use Minds\Core\Rewards\OfacBlacklist;
use Minds\Core\Rewards\JoinedValidator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Blockchain\Wallets\OffChain\Transactions;
use Minds\Core\SMS\SMSServiceInterface;
use Minds\Core\Security\TwoFactor;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumber;
use Minds\Entities\User;
use Minds\Core\Data\Call;

class JoinSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Join');
    }

    /*function it_should_send_an_sms_to_verify_the_user(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user,
        Config $config,
        ReferralValidator $validator,
        JoinedValidator $joinedValidator,
        OfacBlacklist $ofacBlacklist,
        Call $db
    )
    {
        $this->beConstructedWith($twofactor, $sms, $libphonenumber, $config, $validator, $db, $joinedValidator, $ofacBlacklist);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->createSecret()
            ->shouldBeCalled()
            ->willReturn('secret');
        $twofactor->getCode('secret')
            ->shouldBeCalled()
            ->willReturn(123456);

        $sms->send('+19293872643', 123456)
            ->shouldBeCalled();

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $db->insert("rewards:verificationcode:123", ['code'=>'123456', 'secret'=>'secret'])
            ->shouldBeCalled()
            ->willReturn(null);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643");
        $this->verify()->shouldReturn('secret');
    }

    function it_should_check_ofac_blacklist(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user,
        Config $config,
        ReferralValidator $validator,
        JoinedValidator $joinedValidator,
        OfacBlacklist $ofacBlacklist,
        Call $db
    )
    {
        $this->beConstructedWith($twofactor, $sms, $libphonenumber, $config, $validator, $db, $joinedValidator, $ofacBlacklist);

        $ofacBlacklist->isBlacklisted("53 8363564")
            ->shouldBeCalled()
            ->willReturn(true);

        $this->shouldThrow('\Exception')->duringSetNumber("53 8363564");
    }

    function it_should_confirm_a_number(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user
    )
    {
        $this->beConstructedWith($twofactor, $sms, $libphonenumber);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->verifyCode('secret', 123456, 8)
            ->shouldBeCalled()
            ->willReturn(true);

        //$user->setPhoneNumber('+19293872643');
        //$user->setPhoneNumberHash(sha1('+19293872643'));

        $user->get('referrer')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643")
            ->setCode(123456)
            ->setSecret('secret');
        $this->confirm()->shouldReturn(true);
    }

    function it_should_throw_exception_if_code_is_wrong(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user
    )
    {
        $this->beConstructedWith($twofactor, $sms, $libphonenumber);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->verifyCode('secret', 123456, 8)
            ->shouldBeCalled()
            ->willReturn(false);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643")
            ->setCode(123456)
            ->setSecret('secret');
        $this->shouldThrow('\Exception')->duringConfirm();
    }

    function it_should_store_the_referral_when_confirming(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user,
        Config $config,
        ReferralValidator $validator,
        JoinedValidator $joinedValidator,
        Client $esClient,
        Call $db
    )
    {
        Di::_()->bind('Database\ElasticSearch', function ($di) use ($esClient) {
            return $esClient->getWrappedObject();
        }, ['useFactory' => false]);

        $this->beConstructedWith($twofactor, $sms, $libphonenumber, $config, $validator, $db, $joinedValidator);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->verifyCode('secret', 123456, 8)
            ->shouldBeCalled()
            ->willReturn(true);

        $user->setPhoneNumber('+19293872643');
        //$user->setPhoneNumberHash(sha1('+19293872643'));

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $user->get('referrer')
            ->shouldBeCalled()
            ->willReturn('1234');

        $validator->setHash(Argument::any())
            ->shouldBeCalled()
            ->willReturn($validator);
        $validator->validate()
            ->shouldBeCalled()
            ->willReturn(true);

        $joinedValidator->setHash(Argument::any())
            ->shouldBeCalled()
            ->willReturn($joinedValidator);
        $joinedValidator->validate()
            ->shouldBeCalled()
            ->willReturn(false);

        $esClient->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643")
            ->setCode(123456)
            ->setSecret('secret');
        $this->confirm()->shouldReturn(true);
    }

    function it_should_create_transaction_when_join_confirming(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user,
        Config $config,
        ReferralValidator $validator,
        JoinedValidator $joinedValidator,
        Client $esClient,
        Call $db
    )
    {
        Di::_()->bind('Database\ElasticSearch', function ($di) use ($esClient) {
            return $esClient->getWrappedObject();
        }, ['useFactory' => false]);

        $this->beConstructedWith($twofactor, $sms, $libphonenumber, $config, $validator, $db, $joinedValidator);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->verifyCode('secret', 123456, 8)
            ->shouldBeCalled()
            ->willReturn(true);

        $user->setPhoneNumber('+19293872643');
        //$user->setPhoneNumberHash(sha1('+19293872643'));

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $user->get('referrer')
            ->shouldBeCalled()
            ->willReturn('1234');

        $validator->setHash(Argument::any())
            ->shouldBeCalled()
            ->willReturn($validator);
        $validator->validate()
            ->shouldBeCalled()
            ->willReturn(true);

        $joinedValidator->setHash(Argument::any())
            ->shouldBeCalled()
            ->willReturn($joinedValidator);
        $joinedValidator->validate()
            ->shouldBeCalled()
            ->willReturn(false);

        $esClient->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643")
            ->setCode(123456)
            ->setSecret('secret');
        $this->confirm()->shouldReturn(true);
    }

    function it_should_not_store_the_referral_when_confirming(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock,
        User $user,
        Config $config,
        ReferralValidator $validator
    )
    {

        $this->beConstructedWith($twofactor, $sms, $libphonenumber, $config, $validator);

        $libphonenumber->parse("+1 929 387 2643")
            ->shouldBeCalled()
            ->willReturn($phonenumberMock);

        $libphonenumber->format($phonenumberMock, Argument::any())
            ->shouldBeCalled()
            ->willReturn('+19293872643');

        $twofactor->verifyCode('secret', 123456, 8)
            ->shouldBeCalled()
            ->willReturn(true);

        $user->setPhoneNumber('+19293872643');
        //$user->setPhoneNumberHash(sha1('+19293872643'));

        $user->get('guid')
            ->shouldBeCalled()
            ->willReturn('123');

        $user->get('referrer')
            ->shouldBeCalled()
            ->willReturn('1234');

        $validator->setHash(Argument::any())
            ->shouldBeCalled()
            ->willReturn($validator);
        $validator->validate()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->getWrappedObject()
            ->setUser($user)
            ->setNumber("1 929 387 2643")
            ->setCode(123456)
            ->setSecret('secret');
        $this->confirm()->shouldReturn(true);
    }*/

}
