<?php
/**
 * Snapshotable.
 *
 * @author emi
 */

namespace Minds\Traits;

trait Snapshotable
{
    /**
     * @return mixed[]
     * @throws \Exception
     */
    public function snapshot()
    {
        $snapshotable = $this->getSnapshotable();

        if ($snapshotable === false) {
            throw new \Exception('Object is not snapshotable');
        }

        $properties = get_object_vars($this);

        if ($snapshotable === true) {
            return $properties;
        }

        return array_filter($properties, function ($key) use ($snapshotable) {
            return in_array($key, $snapshotable);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * @param array $properties
     * @return $this
     */
    public function restore($properties = [])
    {
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }

        return $this;
    }

    /**
     * @return string[]|bool
     */
    abstract function getSnapshotable();
}
