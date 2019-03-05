<?php
/**
 * Issue Manager
 * @author Martin Santangelo <martin@minds.com>
 */
namespace Minds\Core\Issues;

use Minds\Core\Di\Di;
use Minds\Core\Issues\Issue;
use Minds\Core\Issues\Services\Gitlab;
use Minds\Core\Issues\Contracts\PostIssueInterface;

class Manager implements PostIssueInterface
{
    /** @var Gitlab $service */
    private $service;

    public function __construct(Gitlab $service = null)
    {
        $this->service = $service ?: Di::_()->get('Issues\Service\Gitlab');
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
        return $this->service->postIssue($issue, $project);
    }
}