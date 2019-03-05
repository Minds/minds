<?php

namespace Spec\Minds\Core\Issues\Services;

use Minds\Core\Config;
use Minds\Core\Issues\Issue;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Minds\Core\Http\Curl\Json\Client as JsonClient;

class GitlabSpec extends ObjectBehavior
{
    /** @var Config $config */
    private $_config;

    /** @var Client $http */
    private $_jsonClient;

    function let(JsonClient $jsonClient, Config $config)
    {
        $this->_config = $config;
        $this->_jsonClient = $jsonClient;

        $this->beConstructedWith('privatekey', $jsonClient, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Issues\Services\Gitlab');
    }

    function it_should_return_headers()
    {
        $this->getHeaders()->shouldReturn(['PRIVATE-TOKEN: privatekey']);
    }

    function it_should_return_project_base_url()
    {
        $project = 'mobile';
        $this->_config->get('gitlab')->willReturn([
            'project_id' => [$project => 1000000]
        ]);
        $this->getBaseUrl($project)->shouldReturn('https://gitlab.com/api/v4/projects/1000000');
    }

    function it_should_post_to_gitlab()
    {
        $project = 'mobile';
        $this->_config->get('gitlab')->willReturn([
            'project_id' => [$project => 1000000]
        ]);

        $issue = new Issue;
        $issue->setTitle('title')
            ->setDescription('issue desc')
            ->setLabels('by user');

        $this->_jsonClient->post('https://gitlab.com/api/v4/projects/1000000/issues', [
            'title'       => $issue->getTitle(),
            'labels'      => $issue->getLabels(),
            'description' => $issue->getDescription(),
            'confidential' => true,
        ], [
            'headers' => ['PRIVATE-TOKEN: privatekey']
        ])->willReturn(['result' => ['foo' => 'bar']]);

        $this->postIssue($issue, $project)->shouldReturn(['result' => ['foo' => 'bar']]);
    }
}
