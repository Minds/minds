<?php

/**
 * Minds Withdraw contract
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Contracts;

class MindsWithdraw extends ExportableContract
{
    /**
     * @return array
     */
    public function getABI()
    {
        return json_decode('[
            {
              "constant": false,
              "inputs": [
                {
                  "name": "requester",
                  "type": "address"
                },
                {
                  "name": "user_guid",
                  "type": "uint256"
                },
                {
                  "name": "gas",
                  "type": "uint256"
                },
                {
                  "name": "amount",
                  "type": "uint256"
                }
              ],
              "name": "complete",
              "outputs": [
                {
                  "name": "",
                  "type": "bool"
                }
              ],
              "payable": false,
              "stateMutability": "nonpayable",
              "type": "function"
            },
            {
              "constant": true,
              "inputs": [
                {
                  "name": "",
                  "type": "uint256"
                }
              ],
              "name": "requests",
              "outputs": [
                {
                  "name": "requester",
                  "type": "address"
                },
                {
                  "name": "user_guid",
                  "type": "uint256"
                },
                {
                  "name": "gas",
                  "type": "uint256"
                },
                {
                  "name": "amount",
                  "type": "uint256"
                }
              ],
              "payable": false,
              "stateMutability": "view",
              "type": "function"
            },
            {
              "constant": false,
              "inputs": [
                {
                  "name": "user_guid",
                  "type": "uint256"
                },
                {
                  "name": "amount",
                  "type": "uint256"
                }
              ],
              "name": "request",
              "outputs": [],
              "payable": true,
              "stateMutability": "payable",
              "type": "function"
            },
            {
              "constant": true,
              "inputs": [],
              "name": "token",
              "outputs": [
                {
                  "name": "",
                  "type": "address"
                }
              ],
              "payable": false,
              "stateMutability": "view",
              "type": "function"
            },
            {
              "inputs": [
                {
                  "name": "_token",
                  "type": "address"
                }
              ],
              "payable": false,
              "stateMutability": "nonpayable",
              "type": "constructor"
            },
            {
              "payable": true,
              "stateMutability": "payable",
              "type": "fallback"
            },
            {
              "anonymous": false,
              "inputs": [
                {
                  "indexed": false,
                  "name": "requester",
                  "type": "address"
                },
                {
                  "indexed": false,
                  "name": "user_guid",
                  "type": "uint256"
                },
                {
                  "indexed": false,
                  "name": "gas",
                  "type": "uint256"
                },
                {
                  "indexed": false,
                  "name": "amount",
                  "type": "uint256"
                }
              ],
              "name": "WithdrawalRequest",
              "type": "event"
            },
            {
              "anonymous": false,
              "inputs": [
                {
                  "indexed": false,
                  "name": "requester",
                  "type": "address"
                },
                {
                  "indexed": false,
                  "name": "user_guid",
                  "type": "uint256"
                },
                {
                  "indexed": false,
                  "name": "amount",
                  "type": "uint256"
                }
              ],
              "name": "WithdrawalComplete",
              "type": "event"
            }
          ]', true);
    }
}
