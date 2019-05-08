<?php

/**
 * ElasticSearch Delete
 *
 * @author edgebal
 */

namespace Minds\Core\Data\ElasticSearch\Prepared;

use Minds\Core\Data\Interfaces\PreparedMethodInterface;

class Delete implements PreparedMethodInterface
{
    protected $_query;

    /**
     * @param array $params
     */
    public function query(array $params)
    {
        $this->_query = array_merge([
            'client' => [
                'timeout' => 2,
                'connect_timeout' => 1,
            ],
        ], $params);
    }

    /**
     * Build the prepared request
     * @return array
     */
    public function build()
    {
        return $this->_query;
    }

    /**
     * Return options for the query
     */
    public function getOpts()
    {
    }

    /**
     * Sets the prepared method
     * @return string
     */
    public function getMethod()
    {
        return 'delete';
    }
}
