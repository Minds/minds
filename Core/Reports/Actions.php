<?php
namespace Minds\Core\Reports;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Core\Events\Dispatcher;

class Actions
{
    /** @var Core\Entities\Actions\Save */
    protected $saveAction;

    /** @var Core\Reports\Repository */
    protected $repository;

    /** @var Entities\Factory */
    protected $entitiesFactory;

    /** @var Core\Events\EventsDispatcher */
    protected $dispatcher;

    public function __construct(
        $saveAction = null,
        $repository = null,
        $entitiesFactory = null,
        $dispatcher = null
    ) {
        $this->saveAction = $saveAction ?: new Core\Entities\Actions\Save();
        $this->repository = $repository ?: Di::_()->get('Reports\Repository');
        $this->entitiesFactory = $entitiesFactory ?: Di::_()->get('Entities\Factory');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * @param string|int $guid
     * @return bool
     */
    public function archive($guid)
    {
        if (!$guid) {
            return false;
        }

        $done = $this->repository->update($guid, [
            'state' => 'archived'
        ]);

        return (bool) $done;
    }

    /**
     * @param string|int $guid
     * @param string
     * @return bool
     * @throws \Exception
     */
    public function markAsExplicit($guid, $reason = null)
    {
        if (!$guid) {
            return false;
        }

        $report = $this->repository->getRow($guid);

        if (!$report) {
            return false;
        }

        if ($reason) {
            $report->setReason($reason);
        }

        $entityId = $report->getEntityLuid() ?: $report->getEntityGuid();
        $entity = $this->entitiesFactory->build($entityId); // Most updated version

        if (!$entity) {
            throw new \Exception('Entity not found');
        }

        // Main
        $dirty = $this->setMatureFlag($entity, true);

        if ($dirty) {
            $this->saveAction
                ->setEntity($entity)
                ->save();
        }

        // Attachment and/or embedded entity
        $props = [ 'attachment_guid', 'entity_guid' ];

        foreach ($props as $prop) {
            if ($entity->{$prop}) {
                $rel = $this->entitiesFactory->build($entity->{$prop});

                if ($rel) {
                    $dirty = $this->setMatureFlag($rel, true);

                    if ($dirty) {
                        $this->saveAction
                            ->setEntity($rel)
                            ->save();
                    }
                }
            }
        }

        $this->dispatcher->trigger('notification', 'all', [
            'to'=> [$entity->owner_guid],
            'from' => 100000000000000519,
            'notification_view' => 'report_actioned',
            'entity' => $entity,
            'params' => [
                'action' => 'marked as Explicit',
            ]
        ]);

        $success = $this->repository->update($guid, [
            'state' => 'actioned',
            'action' => 'explicit',
            'reason' => (string) $report->getReason(),
        ]);

        return (bool) $success;
    }

    /**
     * @param string|int $guid
     * @param string
     * @return bool
     * @throws \Exception
     */
    public function markAsSpam($guid, $reason = null)
    {
        if (!$guid) {
            return false;
        }

        $report = $this->repository->getRow($guid);

        if (!$report) {
            return false;
        }

        if ($reason) {
            $report->setReason($reason);
        }

        $entityId = $report->getEntityLuid() ?: $report->getEntityGuid();
        $entity = $this->entitiesFactory->build($entityId); // Most updated version

        if (!$entity) {
            throw new \Exception('Entity not found');
        }

        // Main
        $dirty = $this->setSpamFlag($entity, true);

        if ($dirty) {
            $this->saveAction
                ->setEntity($entity)
                ->save();
        }

        // Attachment and/or embedded entity
        $props = [ 'attachment_guid', 'entity_guid' ];

        foreach ($props as $prop) {
            if ($entity->{$prop}) {
                $rel = $this->entitiesFactory->build($entity->{$prop});

                if ($rel) {
                    $dirty = $this->setSpamFlag($rel, true);

                    if ($dirty) {
                        $this->saveAction
                            ->setEntity($rel)
                            ->save();
                    }
                }
            }
        }

        $this->dispatcher->trigger('notification', 'all', [
            'to'=> [$entity->owner_guid],
            'from' => 100000000000000519,
            'notification_view' => 'report_actioned',
            'entity' => $entity,
            'params' => [
                'action' => 'marked as Spam',
            ]
        ]);

        $success = $this->repository->update($guid, [
            'state' => 'actioned',
            'action' => 'spam',
            'reason' => (string) $report->getReason(),
        ]);

        return (bool) $success;
    }

    /**
     * @param string|int $guid
     * @param string
     * @return bool
     * @throws \Exception
     */
    public function delete($guid, $reason = null)
    {
        if (!$guid) {
            return false;
        }

        $report = $this->repository->getRow($guid);

        if (!$report) {
            return false;
        }

        if ($reason) {
            $report->setReason($reason);
        }

        $entityId = $report->getEntityLuid() ?: $report->getEntityGuid();
        $entity = $this->entitiesFactory->build($entityId); // Most updated version

        if (!$entity) {
            throw new \Exception('Entity not found');
        }

        // Main
        $dirty = $this->setDeletedFlag($entity, true);

        if ($dirty) {
            $this->saveAction
                ->setEntity($entity)
                ->save();
        }

        // Attachment and/or embedded entity
        $props = [ 'attachment_guid', 'entity_guid' ];

        foreach ($props as $prop) {
            if ($entity->{$prop}) {
                $rel = $this->entitiesFactory->build($entity->{$prop});

                if ($rel) {
                    $dirty = $this->setDeletedFlag($rel, true);

                    if ($dirty) {
                        $this->saveAction
                            ->setEntity($rel)
                            ->save();
                    }
                }
            }
        }

        $this->dispatcher->trigger('notification', 'all', [
            'to'=> [$entity->owner_guid],
            'from' => 100000000000000519,
            'notification_view' => 'report_actioned',
            'entity' => $entity,
            'params' => [
                'action' => 'Deleted',
            ]
        ]);

        $success = $this->repository->update($guid, [
            'state' => 'actioned',
            'action' => 'delete',
            'reason' => (string) $report->getReason(),
        ]);

        return (bool) $success;
    }

    /**
     * @param Entities\Report $report
     */
    public function undo(Entities\Report $report)
    {
        $action = $report->getAction();

        if (!$action) {
            return false;
        }

        $entityId = $report->getEntityLuid() ?: $report->getEntityGuid();
        $entity = $this->entitiesFactory->build($entityId); // Most updated version

        if (!$entity) {
            throw new \Exception('Entity not found');
        }

        switch ($action) {
            case 'explicit':
                // Main
                $dirty = $this->setMatureFlag($entity, false);

                if ($dirty) {
                    $this->saveAction
                        ->setEntity($entity)
                        ->save();
                }

                // Attachment and/or embedded entity
                $props = [ 'attachment_guid', 'entity_guid' ];

                foreach ($props as $prop) {
                    if ($entity->{$prop}) {
                        $rel = $this->entitiesFactory->build($entity->{$prop});

                        if ($rel) {
                            $dirty = $this->setSpamFlag($rel, false);

                            if ($dirty) {
                                $this->saveAction
                                    ->setEntity($rel)
                                    ->save();
                            }
                        }
                    }
                }
                break;

            case 'spam':
                // Main
                $dirty = $this->setSpamFlag($entity, false);

                if ($dirty) {
                    $this->saveAction
                        ->setEntity($entity)
                        ->save();
                }

                // Attachment and/or embedded entity
                $props = [ 'attachment_guid', 'entity_guid' ];

                foreach ($props as $prop) {
                    if ($entity->{$prop}) {
                        $rel = $this->entitiesFactory->build($entity->{$prop});

                        if ($rel) {
                            $dirty = $this->setSpamFlag($rel, false);

                            if ($dirty) {
                                $this->saveAction
                                    ->setEntity($rel)
                                    ->save();
                            }
                        }
                    }
                }
                break;

            case 'delete':
                // Main
                $dirty = $this->setDeletedFlag($entity, false);

                if ($dirty) {
                    $this->saveAction
                        ->setEntity($entity)
                        ->save();
                }

                // Attachment and/or embedded entity
                $props = [ 'attachment_guid', 'entity_guid' ];

                foreach ($props as $prop) {
                    if ($entity->{$prop}) {
                        $rel = $this->entitiesFactory->build($entity->{$prop});

                        if ($rel) {
                            $dirty = $this->setDeletedFlag($rel, false);

                            if ($dirty) {
                                $this->saveAction
                                    ->setEntity($rel)
                                    ->save();
                            }
                        }
                    }
                }
                break;
        }

        return true;
    }

    /**
     * Enable/disabled the maturity flag for an entity
     * @param  mixed $entity
     * @param bool $value
     * @return bool
     */
    protected function setMatureFlag($entity = null, $value = false)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $value = !!$value;

        $dirty = false;

        // Main mature flag
        if (method_exists($entity, '_magicAttributes') || method_exists($entity, 'setMature')) {
            $entity->setMature($value);
            $dirty = true;
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('mature', $value);
            $dirty = true;
        } elseif (property_exists($entity, 'mature')) {
            $entity->mature = $value;
            $dirty = true;
        }

        // Custom Data
        if (method_exists($entity, 'setCustom') && $entity->custom_data && is_array($entity->custom_data)) {
            $custom_data = $entity->custom_data;

            if (isset($custom_data[0])) {
                $custom_data[0]['mature'] = $value;
            } else {
                $custom_data['mature'] = $value;
            }

            $entity->setCustom($entity->custom_type, $custom_data);
            $dirty = true;
        }

        return $dirty;
    }

    /**
     * Enable/disable the spam flag for an entity
     * @param  mixed $entity
     * @param bool $value
     * @return bool
     */
    protected function setSpamFlag($entity = null, $value = false)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $value = !!$value;

        $dirty = false;

        // Main mature flag
        if (method_exists($entity, '_magicAttributes') || method_exists($entity, 'setSpam')) {
            $entity->setSpam($value);
            $dirty = true;
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('spam', $value);
            $dirty = true;
        } elseif (property_exists($entity, 'spam')) {
            $entity->spam = $value;
            $dirty = true;
        }

        return $dirty;
    }

    /**
     * Enable/disable the delete flag for an entity
     * @param  mixed $entity
     * @param bool $value
     * @return bool
     */
    protected function setDeletedFlag($entity = null, $value = false)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $value = !!$value;

        $dirty = false;

        // Main mature flag
        if (method_exists($entity, '_magicAttributes') || method_exists($entity, 'setDeleted')) {
            $entity->setDeleted($value);
            $dirty = true;
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('deleted', $value);
            $dirty = true;
        } elseif (property_exists($entity, 'deleted')) {
            $entity->deleted = $value;
            $dirty = true;
        }

        return $dirty;
    }
}
