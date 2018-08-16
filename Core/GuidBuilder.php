<?php


namespace Minds\Core;


class GuidBuilder
{
    public function __get($name)
    {
        if ($name === 'socket') {
            return Guid::$socket;
        }
    }

    public function build()
    {
        return Guid::build();
    }

    public function connect()
    {
        return Guid::connect();
    }
}