<?php

/**
 * Minds Wire contract
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Contracts;

class MindsWire extends ExportableContract
{
    /**
     * @return array
     */
    public function getABI()
    {
        return json_decode('[{"constant":true,"inputs":[],"name":"canIWire","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"sender","type":"address"},{"name":"receiver","type":"address"},{"name":"amount","type":"uint256"}],"name":"wireFrom","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"s","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"_from","type":"address"},{"name":"_value","type":"uint256"},{"name":"_tokenContract","type":"address"},{"name":"_extraData","type":"bytes"}],"name":"receiveApproval","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":false,"inputs":[{"name":"receiver","type":"address"},{"name":"amount","type":"uint256"}],"name":"wire","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[{"name":"receiver","type":"address"},{"name":"amount","type":"uint256"},{"name":"timestamp","type":"uint256"}],"name":"hasSent","outputs":[{"name":"","type":"bool"}],"payable":false,"type":"function"},{"constant":true,"inputs":[],"name":"token","outputs":[{"name":"","type":"address"}],"payable":false,"type":"function"},{"inputs":[{"name":"_storage","type":"address"},{"name":"_token","type":"address"}],"payable":false,"type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"name":"sender","type":"address"},{"indexed":false,"name":"receiver","type":"address"},{"indexed":false,"name":"amount","type":"uint256"}],"name":"WireSent","type":"event"}]', true);
    }
}
