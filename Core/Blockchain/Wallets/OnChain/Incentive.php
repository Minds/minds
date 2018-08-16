<?php

/**
 * Minds OnChain Wallet Incentive
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Wallets\OnChain;

use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Incentive
{
    /** @var Config */
    protected $config;

    /** @var Ethereum */
    protected $eth;

    /** @var User */
    protected $user;

    /** @var EventsDispatcher */
    protected $dispatcher;

    public function __construct($config = null, $eth = null, $dispatcher = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * @param User $user
     * @return Incentive
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param array $options
     * @return bool
     * @throws \Exception
     * @throws \Minds\Exceptions\StopEventException
     */
    public function send(array $options = [])
    {
        $options = array_merge([
            'notification' => true,
        ], $options);

        if (!$this->user->getEthWallet() || $this->user->getEthIncentive()) {
            // No wallet or already sent
            //return false;
        }

        $config = $this->config->get('blockchain');

        if (!$config['testnet']) {
            return false;
        }

        $privateKey = $config['incentive_wallet_pkey'];

        $txHash = $this->eth->sendRawTransaction($privateKey, [
            'from' => $config['incentive_wallet_address'],
            'to' => $this->user->getEthWallet(),
            'gasLimit' => BigNumber::_(300000)->toHex(true),
            'value' => $this->getIncentive()->toHex(true),
        ]);

        $this->user->setEthIncentive($txHash);
        $this->user->save();

        if ($options['notification']) {
            $address = substr($this->user->getEthWallet(), 0, 5) . '...' . substr($this->user->getEthWallet(), -5);
            $message = 'Hey! We\'ve sent you 0.002 ETH to your wallet ' . $address . '. It might take some minutes to arrive.';

            $this->dispatcher->trigger('notification', 'onchain:incentive', [
                'to' => [ $this->user->guid ],
                'from' => 100000000000000519,
                'notification_view' => 'custom_message',
                'params' => [ 'message' => $message ],
                'message' => $message,
            ]);
        }

        return true;
    }

    /**
     * @return BigNumber
     * @throws \Exception
     */
    protected function getIncentive()
    {
        return BigNumber::toPlain('0.002', 18);
    }
}
