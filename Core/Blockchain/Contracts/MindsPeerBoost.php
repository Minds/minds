<?php

/**
 * Minds Peer Boost contract
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Contracts;

class MindsPeerBoost extends ExportableContract
{
    /**
     * @return array
     */
    public function getABI()
    {
        return json_decode('[{"constant":false,"inputs":[{"name":"guid","type":"uint256"}],"name":"accept","outputs":[],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"sender","type":"address"},{"name":"guid","type":"uint256"},{"name":"receiver","type":"address"},{"name":"amount","type":"uint256"}],"name":"boostFrom","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"guid","type":"uint256"}],"name":"revoke","outputs":[],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"guid","type":"uint256"},{"name":"receiver","type":"address"},{"name":"amount","type":"uint256"}],"name":"boost","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"s","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"canIBoost","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_value","type":"uint256"},{"name":"_tokenContract","type":"address"},{"name":"_extraData","type":"bytes"}],"name":"receiveApproval","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"guid","type":"uint256"}],"name":"reject","outputs":[],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"token","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"inputs":[{"name":"_storage","type":"address"},{"name":"_token","type":"address"}],"payable":false,"type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"name":"guid","type":"uint256"}],"name":"PeerBoostSent","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"guid","type":"uint256"}],"name":"PeerBoostAccepted","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"guid","type":"uint256"}],"name":"PeerBoostRejected","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"name":"guid","type":"uint256"}],"name":"PeerBoostRevoked","type":"event"}]', true);
    }
}
