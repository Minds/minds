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

    public function setFunction(\Closure $function) : DiBinding
    {
        $this->function = $function;
        return $this;
    }

    public function getFunction() : \Closure
    {
        return $this->function;
    }

    public function setFactory(bool $useFactory) : DiBinding
    {
        $this->isFactory = $useFactory;
        return $this;
    }

    public function isFactory() : bool
    {
        return $this->isFactory;
    }

    public function setImmutable(bool $immutable) : DiBinding
    {
        $this->isImmutable = $immutable;
        return $this;
    }

    public function isImmutable() : bool
    {
        return $this->isImmutable;
    }

}
