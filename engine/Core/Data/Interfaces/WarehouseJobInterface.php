<?php
/**
 * Warehouse Job Interface
 */

namespace Minds\Core\Data\Interfaces;

interface WarehouseJobInterface{
    
    public function run(array $slugs = array());
    
}   