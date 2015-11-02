<?php
namespace Minds\Core\Queue;

/**
 * Message object
 */
class Message
{
    private $data;
    
    public function __construct($data = null)
    {
        if ($data) {
            $this->data = json_decode($data, true);
        }
    }
 
    /**
     * Serialize and set the data
     * @param mixed $data
     * @return string
     */
    public function setData($data)
    {
        if (is_array($data)) {
            //multisites require that we pass the keyspace
            global $CONFIG;
            $data['keyspace'] = $CONFIG->cassandra->keyspace;
        }
        $this->data = json_encode($data);
        return $this->data;
    }
    
    public function getData()
    {
        return $this->data;
    }
}
