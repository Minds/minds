<?php
/**
 * Post Issue Interface
 * @author Martin Santangelo <martin@minds.com>
 */
namespace Minds\Core\Issues\Contracts;

use Minds\Core\Issues\Issue;

interface PostIssueInterface
{
    /**
     * Post a new issue
     *
     * @param Issue $issue
     * @param string $project
     * @return array
     */
    public function postIssue(Issue $issue, string $project);
}