<?php
/**
 * Prepared query
 */
namespace Minds\Core\Data\Cassandra\Prepared;

use  Minds\Core\Data\Interfaces;

class Custom implements Interfaces\PreparedInterface
{
    private $template;
    private $values;
    
    public function build()
    {
        return array(
            'string' => $this->template,
            'values'=>$this->values
            );
    }

    public function query($cql, $values = array())
    {
        $this->template = $cql;
        $this->values = $values;
        return $this;
    }
}
