<?php
/**
 * Namespaced Trait
 */
namespace Minds\Core\Data\Cassandra\Thrift;

trait Namespaced
{
    protected $namespace;

    /**
     * Sets the current namespace
     * @param string $namespace
     */
    public function setNamespace($namespace = '')
    {
        $this->namespace = $namespace ?: '';
        
        return $this;
    }
    
    /**
     * Gets the current namespace
     * @return [type] [description]
     */
    public function getNamespace()
    {
        return $this->namespace ?: '';
    }
    
    /**
     * Builds an identifier using the current namespace, if any
     * @param  string $name
     * @return string
     */
    public function buildNamespace($name)
    {
        $namespace = '';
        
        if ($this->getNamespace()) {
            $namespace = $this->getNamespace() . ':';
        }
        
        return $namespace . $name;
    }
}
