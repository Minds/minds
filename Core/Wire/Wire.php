<?php
/**
 * Wire model
 */
namespace Minds\Core\Wire;

use Minds\Traits\MagicAttributes;

class Wire
{
    use MagicAttributes;

    /** @var User **/
    private $receiver;

    /** @var User **/
    private $entity;

    /** @var User **/
    private $sender;

    /** @var double **/
    private $amount;

    /** @var bool **/
    private $recurring = false;

    /** @var method **/
    private $method = 'tokens';

    /** @var int $timestamp **/
    private $timestamp;

    public function export() {
        return [
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'receiver' => $this->receiver->export(),
            'sender' => $this->sender->export(),
            'recurring' => $this->recurring,
        ];
    }

}
