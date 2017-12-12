<?php
/**
 * Blockchain Contract interface
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Contracts;


interface BlockchainContractInterface
{
    /**
     * @param string $address
     * @return $this
     */
    public static function at($address);

    /**
     * @return string
     */
    public function getAddress();

    /**
     * @return array
     */
    public function getABI();

    /**
     * @return array
     */
    public function getExtra();
}
