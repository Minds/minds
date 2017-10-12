<?php

/**
 * ElasticSearch Search
 *
 * @author emi
 */

namespace Minds\Core\Data\ElasticSearch\Prepared;

use Minds\Core\Data\Interfaces\PreparedMethodInterface;

class Search implements PreparedMethodInterface
{
    protected $_query;

    /**
     * @param array $params
     */
    public function query(array $params)
    {
        $this->_query = $params;
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
        return 'search';
    }
}
