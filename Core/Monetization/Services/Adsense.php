<?php
namespace Minds\Core\Monetization\Services;

use Minds\Core;

class Adsense
{
    protected $analytics;
    protected $client;

    public function __construct($config = null, $cache = null)
    {
        $this->config = $config ?: Core\Di\Di::_()->get('Config');
        $this->cache = $cache ?: Core\Di\Di::_()->get('Monetization\ServiceCache');

        $this->cache
            ->setService('adsense:b1')
            ->setLongTtl(24 * 60 * 60) // 1 day
            ->setShortTtl(60 * 60); // 1 hour

        $this->initClient();
    }

    protected function initClient()
    {
        $config = $this->config->get('google');

        $client = new \Google_Client();
        $client->setApplicationName('Minds');
        $client->setAuthConfig($config['analytics']['service_account']['key_path']);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->client = new \Google_Service_AnalyticsReporting($client);

        return $this;
    }

    public function getTotalRevenueAndViews($dimension1, $start, $end)
    {
        $cachedData = $this->cache->get('getTotalRevenueAndViews', $dimension1, $start, $end);

        if ($cachedData !== false) {
            return $cachedData;
        }

        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->getAnalyticsView());

        // Set the date range
        $request->setDateRanges($this->buildDateRange($start, $end));

        // Report the revenue
        $request->setMetrics([
            $this->buildMetric('ga:pageviews', 'pageviews'),
            $this->buildMetric('ga:adsenseRevenue', 'adsenseRevenue'),
        ]);

        // Set the user GUID filter
        $request->setDimensionFilterClauses([
            $this->buildSingleDimensionFilterClause('ga:dimension1', 'EXACT', (string) $dimension1),
        ]);

        // Build the request
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);

        $reports = $this->client->reports->batchGet($body);

        // Format the result
        if (!isset($reports[0]->getData()['totals'][0]['values'][0])) {
            return [ 0, 0.0 ];
        }

        $data = [ (int) $reports[0]->getData()['totals'][0]['values'][0], (float) $reports[0]->getData()['totals'][0]['values'][1] ];
        $this->cache->set('getTotalRevenueAndViews', $dimension1, $start, $end, $data);

        return $data;
    }

    public function getTotalRevenue($dimension1, $start, $end)
    {
        list($views, $revenue) = $this->getTotalRevenueAndViews($dimension1, $start, $end);
        return $revenue;
    }

    public function getTotalViews($dimension1, $start, $end)
    {
        list($views, $revenue) = $this->getTotalRevenueAndViews($dimension1, $start, $end);
        return $views;
    }

    public function getRevenuePerPage($dimension1, $start, $end, $pageToken = '', $pageSize = 50)
    {
        $cachedData = $this->cache->get('getRevenuePerPage', [ $dimension1, $pageToken, $pageSize ], $start, $end);

        if ($cachedData !== false) {
            return $cachedData;
        }
        
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->getAnalyticsView());

        // Set the date range
        $request->setDateRanges($this->buildDateRange($start, $end));

        // Report the views and revenue
        $request->setMetrics([
            $this->buildMetric('ga:pageviews', 'pageviews'),
            $this->buildMetric('ga:adsenseRevenue', 'adsenseRevenue'),
        ]);

        // Breakdown by page paths + titles
        $request->setDimensions([
            $this->buildDimension('ga:pagePath'),
            $this->buildDimension('ga:pageTitle'),
        ]);

        // Set the user GUID filter
        $request->setDimensionFilterClauses([
            $this->buildSingleDimensionFilterClause('ga:dimension1', 'EXACT', (string) $dimension1),
        ]);

        // Order by pageviews
        $request->setOrderBys([
            $this->buildOrderBy('ga:pageviews', 'DESCENDING'),
        ]);

        // Pagination
        $request->setPageSize($pageSize);

        if ($pageToken) {
            $request->setPageToken($pageToken);
        }

        // Build the request
        $body = new \Google_Service_AnalyticsReporting_GetReportsRequest();
        $body->setReportRequests([$request]);

        $reports = $this->client->reports->batchGet($body);
        $report = $reports[0];

        $rows = $report->getData()->getRows();

        // Format result
        $items = [];
        foreach ($rows as $row) {
            $items[] = [
                'url' => $row->getDimensions()[0],
                'title' => $row->getDimensions()[1],
                'views' => (int) $row->getMetrics()[0]->getValues()[0],
                'revenue' => (float) $row->getMetrics()[0]->getValues()[1]
            ];
        }

        $data = [ $items, isset($reports[0]->nextPageToken) ? $reports[0]->nextPageToken : '' ];
        $this->cache->set('getRevenuePerPage', [ $dimension1, $pageToken, $pageSize ], $start, $end, $data);

        return $data;
    }

    /* Internal AdSense */

    protected function getAnalyticsView()
    {
        return $this->config->get('google')['analytics']['ads'];
    }

    protected function buildDateRange(\DateTime $start, \DateTime $end)
    {
        $dateRange = new \Google_Service_AnalyticsReporting_DateRange();
        $dateRange->setStartDate($start->format('Y-m-d'));
        $dateRange->setEndDate($end->format('Y-m-d'));

        return $dateRange;
    }

    protected function buildMetric($expression, $alias = '')
    {
        $metric = new \Google_Service_AnalyticsReporting_Metric();
        $metric->setExpression($expression);
        $metric->setAlias($alias ?: $expression);

        return $metric;
    }

    protected function buildDimension($name)
    {
        $dimension = new \Google_Service_AnalyticsReporting_Dimension();
        $dimension->setName($name);

        return $dimension;
    }

    protected function buildSingleDimensionFilterClause($name, $operator, $expressions)
    {
        if (!is_array($expressions)) {
            $expressions = [ $expressions ];
        }

        $dimensionFilter = new \Google_Service_AnalyticsReporting_DimensionFilter();
        $dimensionFilter->setDimensionName($name);
        $dimensionFilter->setOperator($operator);
        $dimensionFilter->setExpressions($expressions);

        $dimensionFilterClause = new \Google_Service_AnalyticsReporting_DimensionFilterClause();
        $dimensionFilterClause->setFilters([$dimensionFilter]);

        return $dimensionFilterClause;
    }

    protected function buildOrderBy($name, $sortOrder = '')
    {
        $orderBy = new \Google_Service_AnalyticsReporting_OrderBy();
        $orderBy->setFieldName($name);

        if ($sortOrder) {
            $orderBy->setSortOrder($sortOrder);
        }

        return $orderBy;
    }
}
