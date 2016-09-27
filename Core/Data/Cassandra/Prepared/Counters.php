<?php
/**
 * Prepared query
 */
namespace Minds\Core\Data\Cassandra\Prepared;

use  Minds\Core\Data\Interfaces;

class Counters implements Interfaces\PreparedInterface
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

    public function update($guid, $metric, $value)
    {
        $this->template = "UPDATE counters SET count = count + ? WHERE guid = ? AND metric = ?";
        $this->values = [ new \Cassandra\Bigint($value), (string) $guid, $metric ];
        return $this;
    }

    public function clear($guid, $metric)
    {
        $this->template = "UPDATE counters SET count = count - count WHERE guid = ? AND metric = ?";
        $this->values = [ (string) $guid, $metric ];
        return $this;
    }

    public function get($guid, $metric)
    {
        $this->template = "SELECT count FROM counters WHERE guid = ? AND metric = ?";
        $this->values = [ (string) $guid, $metric ];
        return $this;
    }

    public function setQuery($template, $values = array())
    {
        $this->template = $template;
        $this->values = $values;
        return $this;
    }
}
