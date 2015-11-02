<?php
/**
 * Minds page interfact. All pages implement this class
 */
namespace Minds\Interfaces;

interface page
{
    public function get($pages);
    
    public function post($pages);
    
    public function put($pages);
    
    public function delete($pages);
}
