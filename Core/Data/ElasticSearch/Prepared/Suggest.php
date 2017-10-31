<?php

/**
 * ElasticSearch Search
 *
 * @author emi
 */

namespace Minds\Core\Data\ElasticSearch\Prepared;

use Minds\Core\Data\Interfaces\PreparedMethodInterface;

class Suggest implements PreparedMethodInterface
{
    /** @var string $_index */
    protected $_index;

    /** @var string $_query */
    protected $_query;

    /** @var array $_params */
    protected $_params;

    /**
     * @param $index
     * @param array $query
     * @param array $params
     */
    public function query($index, $query, array $params = [])
    {
        $this->_index = $index;
        $this->_query = $query;
        $this->_params = $params;
    }

    /**
     * Build the prepared request
     * @return array
     */
    public function build()
    {
        // Query

        $body = [
            'suggest' => [
                'autocomplete' => [
                    'prefix' => $this->_query,
                    'completion' => [
                        'field' => 'suggest'
                    ]
                ]
            ]
        ];

        //
        if (isset($this->_params['size'])) {
            $body['suggest']['autocomplete']['completion']['size'] = $this->_params['size'];
        }

        $request = [
            'index' => $this->_index,
            'body' => $body
        ];

        return $request;
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
