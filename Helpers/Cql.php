<?php

/**
 * CQL Helper
 *
 * @author emi
 */

namespace Minds\Helpers;

class Cql
{
    /**
     * Internal. Converts some Cassandra types onto PHP native ones.
     * Can be called recursively.
     * @param $row
     * @return mixed
     */
    public static function toPrimitiveType($row)
    {
        foreach ($row as $field => $value) {
            if ($value instanceof \Cassandra\Varint) {
                $row[$field] = (string) $value->toInt();
            } elseif ($value instanceof \Cassandra\Decimal) {
                $row[$field] = $value->toDouble();
            } elseif ($value instanceof \Cassandra\Timestamp) {
                $row[$field] = $value->time();
            } elseif ($value instanceof \Cassandra\Map) {
                $row[$field] = array_combine($value->keys(), static::toPrimitiveType($value->values()));
            } elseif ($value instanceof \Cassandra\Set) {
                $row[$field] = static::toPrimitiveType($value->values());
            }
        }

        return $row;
    }
}
