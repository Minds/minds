<?php
namespace Minds\Core\Issues\Services;

use Minds\Core\Di\Di;
use Minds\Core\Config;
use Minds\Core\Http\Curl\Json\Client;
use Minds\Core\Issues\Issue;
use Minds\Core\Issues\Contracts\PostIssueInterface;

/**
 * Gitlab service
 *
 * @author Martin Santangelo <martin@minds.com>
 */
class Gitlab implements PostIssueInterface
{

    /** @var string $privateKey */
    private $privateKey;

    /** @var Config $config */
    private $config;

    /** @var Client $http */
    private $http;

    public function __construct($key = null, $http = null, $config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->privateKey = $key ?: $this->config->get('gitlab')['private_key'];
        $this->http = $http ?: Di::_()->get('Http\Json');
    }

    /**
     * Returns the headers for the api calls
     *
     * @return array
     */
    public function getHeaders()
    {
        return [ 'PRIVATE-TOKEN: '.$this->privateKey ];
    }

    /**
     * Returns the base url for the api calls
     *
     * @param string $project
     * @return string
     */
    public function getBaseUrl($project)
    {
        $proyectId = $this->config->get('gitlab')['project_id'][$project];
        return 'https://gitlab.com/api/v4/projects/'.$proyectId;
    }

    /**
     * Post a new issue
     *
     * @param Issue $issue
     * @param string $project
     * @return array
     */
    public function postIssue(Issue $issue, string $project)
    {
        $baseUrl = $this->getBaseUrl($project);
        $endpoint = $baseUrl.'/issues';

        $response = $this->http->post($endpoint, [
            'title'       => $issue->getTitle(),
            'labels'      => $issue->getLabels(),
            'description' => $issue->getDescription(),
            'confidential' => true,
        ], [
            'headers' => $this->getHeaders()
        ]);

        if (!is_array($response)) {
            throw new \Exception('Invalid response');
        }

        return $response;
    }
}
