<?php
namespace Minds\Core\Reports;

use Minds\Core;
use Minds\Core\Di\Di;

class Appeals
{
    public function appeal($guid, $user, $note)
    {
        if (!$guid || !$user) {
            throw new \Exception('Missing parameters');
        }

        if (is_object($user)) {
            $user = $user->guid;
        } elseif (is_array($user)) {
            $user = $user['guid'];
        }

        /** @var Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        $report = $repository->getRow($guid);

        if (!$report) {
            throw new \Exception('Report doesn\'t exist');
        }

        if ($report->getOwnerGuid() != $user) {
            throw new \Exception('That report doesn\'t belong to you');
        }

        if ($report->getState() !== 'actioned') {
            throw new \Exception('That report cannot be appealed');
        }

        $done = $repository->update($guid, [
            'appeal_note' => substr($note, 0, 5000),
            'state' => 'appealed'
        ]);

        return $done;
    }

    public function approve($guid)
    {
        if (!$guid) {
            throw new \Exception('Missing parameters');
        }

        /** @var Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        /** @var Actions $actions */
        $actions = Di::_()->get('Reports\Actions');

        $report = $repository->getRow($guid);

        if (!$report) {
            throw new \Exception('Report doesn\'t exist');
        }

        if ($report->getState() !== 'appealed') {
            throw new \Exception('That appeal cannot be rejected');
        }

        $actions->undo($report);

        $done = $repository->update($guid, [
            'state' => 'appeal_approved'
        ]);

        // TODO: Send notification

        return $done;
    }

    public function reject($guid)
    {
        if (!$guid) {
            throw new \Exception('Missing parameters');
        }

        /** @var Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        $report = $repository->getRow($guid);

        if (!$report) {
            throw new \Exception('Report doesn\'t exist');
        }

        if ($report->getState() !== 'appealed') {
            throw new \Exception('That appeal cannot be rejected');
        }

        $done = $repository->update($guid, [
            'state' => 'appeal_rejected'
        ]);

        // TODO: Send notification

        return $done;
    }
}
