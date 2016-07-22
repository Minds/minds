<?php
/**
 * Minds DI Binding Object
 */
namespace Minds\Core\Di;

class Binding
{
    private $function;
    private $isFactory;
    private $isImmutable;

    public function setFunction(\Closure $function)
    {
        $this->function = $function;
        return $this;
    }

    public function getFunction()
    {
        return $this->function;
    }

    public function setFactory($useFactory)
    {
        $this->isFactory = $useFactory;
        return $this;
    }

    public function isFactory()
    {
        return $this->isFactory;
    }

    public function setImmutable($immutable)
    {
        $this->isImmutable = $immutable;
        return $this;
    }

    public function isImmutable()
    {
        return $this->isImmutable;
    }
}
