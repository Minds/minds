<?php
/**
 * Prepared query
 */
namespace Minds\Core\Data\Cassandra\Prepared;

use  Minds\Core\Data\Interfaces;

class System implements Interfaces\PreparedInterface{
    
    private $template;
    private $values; 
    
    public function build(){
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
            
    }
    
    /**
     * Create a table
     * 
     * @param string $table - the table name
     * @param array $columns. Column name => Type
     * @return $this
     */
    public function createTable($table, $columns = array()){
    }
    
    /**
     * Alter a table, add a column
     * 
     * @param string $table
     * @param string $column_name
     * @param string $column_type
     * @return $this
     */
    public function alterTableAddColumn($table, $column_name, $column_type){
        $template = "ALTER TABLE $table ADD $column_name $column_type";
        
        $this->template = $template;
        return $this;
    }
    
}