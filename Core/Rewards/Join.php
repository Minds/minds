<?php
/**
 * Join the rewards program
 */
namespace Minds\Core\Rewards;

use Minds\Core\Di\Di;
use Minds\Core;
use Minds\Entities\User;
use Minds\Core\Util\BigNumber;

class Join
{

    /** @var TwoFactor $twofactor */
    private $twofactor;

    /** @var SMSServiceInterface $sms */
    private $sms;

    /** @var PhoneNumberUtil $libphonenumber */
    private $libphonenumber;

    /** @var User $user */
    private $user;

    /** @var int $number */
    private $number;

    /** @var int $code */
    private $code;

    /** @var string $secret */
    private $secret;

    /** @var Config $config */
    private $config;

    /** @var ReferralValidator */
    private $validator;

    public function __construct(
        $twofactor = null,
        $sms = null,
        $libphonenumber = null,
        $config = null,
        $validator = null,
        $joinedValidator = null
    )
    {
        $this->twofactor = $twofactor ?: Di::_()->get('Security\TwoFactor');
        $this->sms = $sms ?: Di::_()->get('SMS');
        $this->libphonenumber = $libphonenumber ?: \libphonenumber\PhoneNumberUtil::getInstance();
        $this->config = $config ?: Di::_()->get('Config');
        $this->validator = $validator ?: Di::_()->get('Rewards\ReferralValidator');
        $this->joinedValidator = $joinedValidator ?: Di::_()->get('Rewards\JoinedValidator');
    }

    public function setUser(&$user)
    {
        $this->user = $user;
        return $this;
    }

    public function setNumber($number)
    {
        $proto = $this->libphonenumber->parse("+$number");
        $this->number = $this->libphonenumber->format($proto, \libphonenumber\PhoneNumberFormat::E164);
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    public function verify()
    {
        $secret = $this->twofactor->createSecret();
        $code = $this->twofactor->getCode($secret);

        $this->sms->send($this->number, $code);

        return $secret;
    }

    public function confirm()
    {
        if ($this->twofactor->verifyCode($this->secret, $this->code, 8)) {
            //$this->user->setPhoneNumber($this->number);
            $hash = hash('sha256', $this->number . $this->config->get('phone_number_hash_salt'));
            $this->user->setPhoneNumberHash($hash);
            $this->user->save();

            $this->joinedValidator->setHash($hash);
            if ($this->joinedValidator->validate()) {
                $event = new Core\Analytics\Metrics\Event();
                $event->setType('action')
                    ->setProduct('platform')
                    ->setUserGuid((string) $this->user->guid)
                    ->setUserPhoneNumberHash($hash)
                    ->setAction('joined')
                    ->push();

                $transactions = Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
                $transactions
                    ->setUser($this->user)
                    ->setType('joined')
                    ->setAmount((string) BigNumber::toPlain(1, 18));

                $transaction = $transactions->create();
            }

            if ($this->user->referrer && $this->user->guid != $this->user->referrer) {
                $this->validator->setHash($hash);

                if ($this->validator->validate()) {
                    $event = new Core\Analytics\Metrics\Event();
                    $event->setType('action')
                        ->setProduct('platform')
                        ->setUserGuid((string) $this->user->guid)
                        ->setUserPhoneNumberHash($hash)
                        ->setEntityGuid((string) $this->user->referrer)
                        ->setEntityType('user')
                        ->setAction('referral')
                        ->push();
                } 
            }
        } else {
            throw new \Exception('The confirmation failed');
        }

        return true;
    }

}
