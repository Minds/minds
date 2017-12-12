<?php

/**
 * Pending Boost Manager
 *
 * @author emi
 */

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Config;
use Minds\Core\Di\Di;

class Pending
{
    /** @var Config $config */
    protected $config;

    /** @var Core\Blockchain\Pending $pendingManager */
    protected $pendingManager;

    /** @var Core\Blockchain\Services\Ethereum $ethereumClient */
    protected $ethereumClient;

    /**
     * Pending constructor.
     * @param Config $config
     */
    public function __construct($config = null, $pendingManager = null, $ethereumClient = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->pendingManager = $pendingManager ?: Di::_()->get('Blockchain\Pending');
        $this->ethereumClient = $ethereumClient ?: Di::_()->get('Blockchain\Services\Ethereum');
    }

    /**
     * @param string $tx_id
     * @param \Minds\Entities\Boost\Network|\Minds\Entities\Boost\Peer $boost
     */
    public function add($tx_id, $boost)
    {
        $this->pendingManager->add([
            'type' => 'boost',
            'tx_id' => $tx_id,
            'sender_guid' => $boost->getOwner()->guid,
            'data' => [
                'type' => $boost->getHandler(),
                'guid' => $boost->getGuid()
            ]
        ]);
    }

    /**
     * @param string $tx_id
     * @param string|int $guid
     * @throws \Exception
     * @return boolean
     */
    public function resolve($tx_id, $guid)
    {
        /** @var Repository $repo */
        $repo = Di::_()->get('Boost\Repository');

        $pending = $this->pendingManager->get('boost', $tx_id);

        if (!$pending) {
            // TODO: Log? Probably race condition.
            throw new \Exception("No pending Boost entry with hash {$tx_id}");
        }

        if ($pending['data']['guid'] != $guid) {
            return false;
        }

        $boost = $repo->getEntity($pending['data']['type'], $pending['data']['guid']);

        if (!$boost || $boost->getOwner()->guid != $pending['sender_guid']) {
            return false;
        }

        $state = 'review';

        if ($pending['data']['type'] == 'peer') {
            $state = 'created';
        }

        $boost
            ->setState($state)
            ->save();

        $this->pendingManager->delete('boost', $tx_id);

        return true;
    }

    public function approve($boost)
    {
        if (is_object($boost)) {
            $boost = $boost->getGuid();
        }

        return $this->ethereumClient->sendRawTransaction($this->config->get('blockchain')['boost_wallet_pkey'], [
            'from' => $this->config->get('blockchain')['boost_wallet_address'],
            'to' => $this->config->get('blockchain')['peer_boost_address'],
            'gasLimit' => Core\Blockchain\Util::toHex(200000),
            'data' => $this->ethereumClient->encodeContractMethod('approve(uint256)', [
                Core\Blockchain\Util::toHex($boost)
            ])
        ]);
    }

    public function reject($boost)
    {
        if (is_object($boost)) {
            $boost = $boost->getGuid();
        }

        return $this->ethereumClient->sendRawTransaction($this->config->get('blockchain')['boost_wallet_pkey'], [
            'from' => $this->config->get('blockchain')['boost_wallet_address'],
            'to' => $this->config->get('blockchain')['peer_boost_address'],
            'gasLimit' => Core\Blockchain\Util::toHex(200000),
            'data' => $this->ethereumClient->encodeContractMethod('reject(uint256)', [
                Core\Blockchain\Util::toHex($boost)
            ])
        ]);
    }
}
