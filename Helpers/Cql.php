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
     * @param $row
     * @return mixed
     */
    public static function parseQueueRowTypes($row)
    {
        foreach ($row as $field => $value) {
            if ($value instanceof \Cassandra\Varint) {
                $row[$field] = $value->toInt();
            } elseif ($value instanceof \Cassandra\Decimal) {
                $row[$field] = $value->toDouble();
            } elseif ($value instanceof \Cassandra\Timestamp) {
                $row[$field] = $value->time();
            }
        }

        return $row;
    }
}
