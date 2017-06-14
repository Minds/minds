<?php
namespace Minds\Core\Trending\Services;

use Minds\Core;

class GoogleAnalytics
{
    protected $config;

    protected $analytics;
    protected $client;

    public function __construct($config = null)
    {
        $this->config = $config ?: Core\Di\Di::_()->get('Config');

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

    public function getByPageViews($pattern, $start, $end, $pageToken = '', $pageSize = 50)
    {
        $request = new \Google_Service_AnalyticsReporting_ReportRequest();
        $request->setViewId($this->getAnalyticsView());

        // Set the date range
        $request->setDateRanges($this->buildDateRange($start, $end));

        // Report the views and revenue
        $request->setMetrics([
            $this->buildMetric('ga:pageviews', 'pageviews'),
        ]);

        // Breakdown by page paths + titles
        $request->setDimensions([
            $this->buildDimension('ga:pagePath'),
        ]);

        // Order by pageviews
        $request->setOrderBys([
            $this->buildOrderBy('ga:pageviews', 'DESCENDING'),
        ]);

        // Set the URL path filter
        $request->setDimensionFilterClauses([
            $this->buildSingleDimensionFilterClause('ga:pagePath', 'REGEXP', (string) $pattern),
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
                'views' => (int) $row->getMetrics()[0]->getValues()[0],
            ];
        }

        $data = [ $items, isset($reports[0]->nextPageToken) ? $reports[0]->nextPageToken : '' ];

        return $data;
    }

    /* Internal Analytics */

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
