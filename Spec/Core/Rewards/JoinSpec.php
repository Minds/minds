<?php

namespace Spec\Minds\Core\Rewards;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\SMS\SMSServiceInterface;
use Minds\Core\Security\TwoFactor;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumber;
use Minds\Entities\User;

class JoinSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Rewards\Join');
    }

    function it_should_send_an_sms_to_verify_the_user(
        TwoFactor $twofactor,
        SMSServiceInterface $sms,
        PhoneNumberUtil $libphonenumber,
        PhoneNumber $phonenumberMock
    )
    {
        $this->beConstructedWith($twofactor, $sms, $libphonenumber);

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

        $this->setNumber("1 929 387 2643");
        $this->verify()->shouldReturn('secret');
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

        $user->setPhoneNumber('+19293872643');
        $user->setPhoneNumberHash(sha1('+19293872643'));

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

}
