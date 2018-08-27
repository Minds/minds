<?php
/**
 * Plus Delegate
 */
namespace Minds\Core\Wire\Delegates;

use Minds\Core\Config;
use Minds\Core\Di\Di;

class Plus
{

    /** @var Config $config */
    private $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * On Wire
     * @param Wire $wire
     * @param string $receiver_address
     * @return Wire $wire
     */
    public function onWire($wire, $receiver_address)
    {
        if ($wire->getReceiver()->guid != $this->config->get('blockchain')['contracts']['wire']['plus_guid']) {
            return $wire; //not sent to plus
        }

        if (
            !(
                $receiver_address == 'offchain'
                || $receiver_address == $this->config->get('blockchain')['contracts']['wire']['plus_address']
            )
        ) {
            return $wire; //not offchain or potential onchain fraud 
        }

        // 20 tokens
        if ($wire->getAmount() != "20000000000000000000") {
            return $wire; //incorrect wire amount sent
        }

        //set the plus period for this user
        $user = $wire->getSender();
        $user->setPlusExpires(strtotime('+30 days', $wire->getTimestamp()));
        $user->save();

        //$wire->setSender($user);
        return $wire;
    }

}