<?php

namespace Minds\Core\Rewards;

use Minds\Core\Di\Di;

/**
 * OFAC blacklist
 */
class OfacBlacklist
{
    protected $blacklist;

    public function __construct($blacklist = null, $libphonenumber = null)
    {
        $this->blacklist = $blacklist ?: Di::_()->get('Config')->get('ofac_backlist') ?: [];
        $this->libphonenumber = $libphonenumber ?: \libphonenumber\PhoneNumberUtil::getInstance();
    }

    /**
     * Returns true if the phone code is in the blacklist.
     * @param string $phone
     * @return bool
     */
    public function isBlacklisted($phone)
    {
        $phoneNumber = $this->libphonenumber->parse("+$phone", null);

        return array_key_exists($phoneNumber->getCountryCode(), $this->blacklist);
    }
}
