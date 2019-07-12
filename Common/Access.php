<?php

namespace Minds\Common;

class Access
{
    const UNLISTED = 0;
    const LOGGED_IN = 1;
    const PUBLIC = 2;
    const UNKNOWN = 99;

    const ACCESS_STRINGS = [
        0 => 'Unlisted',
        1 => 'LoggedIn',
        3 => 'Public'
    ];

    public static function idToString(int $id) : string
    {
        return self::ACCESS_STRINGS[$id] ?? 'Unknown';
    }
}
