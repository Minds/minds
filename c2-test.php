<?php 

// show all the errors
error_reporting(E_ALL);

require('vendors/phpcassa/lib/autoload.php');

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;

// tutorial code starts here
$pool = new ConnectionPool("MindsTest");
$servers = array("192.168.200.18:9160");

//the pool
$pool = new ConnectionPool("MindsTest", $servers);

//the column family
$column_family = new ColumnFamily($pool, 'user');

try{	
	$cf = $column_family;

	$cf->return_format = ColumnFamily::ARRAY_FORMAT;

function get_page($cf, $key, $page, $page_size) {
    $start_col = "";
    $current_page = 0;
    $page_data = null;
    while ($current_page < $page) {
        // fetch one extra column at the end
        $slice = new ColumnSlice($start_col, "", $page_size + 1);
 
        $page_data = $cf->get($key, $slice);
 
//        if ($count($page_data < $page_size + 1)) {
            // we're at the end of the row
  //          break;
    //    }
            
       // // use that extra column as the next start
       // $start_col = $page_data[count($page_data) - 1][0];
       // $page++;
    }   

	return $page_data;    
}   

var_dump( get_page($cf, 'mark', 1,1) );

}catch(Exception $e){
	var_dump($e);
}
/*
try{
var_dump($column_family->get_range("", "", 1000000, null, array("name", "email")));
} catch(Exception $e){

	//var_dump($e);

}
*/
