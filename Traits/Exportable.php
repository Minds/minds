<?php
/**
 * Exportable
 *
 * @author emi
 */

namespace Minds\Traits;

use Minds\Helpers\Text;

/**
 * Exportable behavior for entities.
 *
 * Trait Exportable
 * @package Minds\Traits
 */
trait Exportable
{
    /** @var bool */
    static $exportToSnakeCase = true;

    /**
     * Specifies the exportable properties
     * @return array<string|\Closure>
     */
    abstract public function getExportable();

    /**
     * Exports a member
     * @return array
     */
    public function export()
    {
        $export = [];

        foreach ($this->getExportable() as $prop) {
            if (!($prop instanceof \Closure)) {
                $export[static::$exportToSnakeCase ? Text::snake($prop) : $prop] = $this->_getExportValue($prop);
            } else {
                $export = array_merge($export, $prop($export));
            }
        }

        return $export;
    }

    protected function _getExportValue($prop)
    {
        $getter = 'get' . ucfirst($prop);

        if (!method_exists($this, '_magicAttributes') && method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, '_magicAttributes') && property_exists($this, $prop)) {
            return $this->$getter();
        } elseif (property_exists($this, $prop)) {
            return $this->$prop;
        }

        return null;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->export();
    }
}
