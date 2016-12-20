<?php

namespace Minds\Core\Storage\Services;

interface ServiceInterface
{

    public function open($path, $mode);

    public function close();

    public function write($data);

    public function read($length);

    public function destroy();

}
