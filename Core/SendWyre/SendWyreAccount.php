<?php

namespace Minds\Core\SendWyre;

use Minds\Traits\MagicAttributes;

class SendWyreAccount
{
    use MagicAttributes;

    /** @var string $userGuid */
    protected $userGuid;

    /** @var string sendWyreAccountId */
    protected $sendWyreAccountId;

    public function export()
    {
        $export = [];
        $export['user_guid'] = intval($this->getUserGuid());
        $export['sendWyreAccountId'] = $this->getSendWyreAccountId();

        return $export;
    }
}
