<?php
/**
 * Minds Denormalized Entity
 */
namespace Minds\Entities;

use Minds\Core;
use Minds\Core\Data;
use Minds\Traits;

class DenormalizedEntity
{

    use Traits\Entity;

    private $db;
    private $guid;

    public function __construct($db = NULL)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
    }

    protected function saveToDb($data){

    }

}
