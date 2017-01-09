<?php
/**
 * Prepared query
 */
namespace Minds\Core\Data\Cassandra\Prepared;

use  Minds\Core\Data\Interfaces;

class System implements Interfaces\PreparedInterface
{
    private $template;
    private $values = array();
    
    public function build()
    {
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
    }
    
    /**
     * Create a table
     *
     * @param string $table - the table name
     * @param array $columns. Column name => type
     * @parAM array $primary_keys - $key
     * @return $this
     */
    public function createTable($table, $columns = [], $primary_keys = [], $attributes = [])
    {
        $cql = "CREATE TABLE $table";
        $s = [];
        foreach ($columns as $key => $validator) {
            $s[] = "$key $validator";
        }
        $s[] = " PRIMARY KEY (" . implode(', ', $primary_keys) . ")";
        $cql .= " (" . implode(', ', $s) . ")";

        if ($attributes) {
            $cql .= ' WITH ' . implode(' AND ', $attributes);
        }

        $this->template = $cql;
        return $this;
    }
    
    /**
     * Alter a table, add a column
     *
     * @param string $table
     * @param string $column_name
     * @param string $column_type
     * @return $this
     */
    public function alterTableAddColumn($table, $column_name, $column_type)
    {
        $template = "ALTER TABLE $table ADD $column_name $column_type";
        
        $this->template = $template;
        return $this;
    }
}
