<?php
/**
 * Transactions report
 *
 * @author Martin Santangelo
 */

namespace Minds\Controllers\api\v2\blockchain\transactions;

use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Session;
use Minds\Core\Util\CsvExporter;
use Minds\Core\Blockchain\Reports\Manager;

class reports implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /** @var string */
    private $token_sale_event_wallet;

    /**
     * Contructor
     * @param Core\Config\Config $config
     */
    public function __construct($config = null) {
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->token_sale_event_wallet = $blockchainConfig['contracts']['token_sale_event']['wallet_address'];
    }

    /**
     * Get params
     *
     * @return array
     */
    protected function getParams()
    {
        $params = $_GET;
        unset($params['report']);
        unset($params['_titles']);
        return $params;
    }

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $report = $_GET['report'];
        $withTitles = isset($_GET['_titles']) ? $_GET['_titles'] : false;

        if (!$report) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Report is required'
            ]);
        }

        $params = $this->getParams();

        $manager = new Manager;

        $data = $manager
            ->setReport("Minds\Core\Blockchain\Reports\\$report")
            ->setParams($params)
            ->get();

        // export data
        $export = CsvExporter::create();

        // add title
        if ($withTitles) $export->addLine($manager->getColumns());

        foreach($data as $row) {
            $export->addLine($row);
        }

        $export->close();

        exit();
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
