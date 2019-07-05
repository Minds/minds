<?php
/**
 * Manager.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Snapshots;

use Minds\Core\Channels\Delegates\Artifacts;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var Artifacts\Factory */
    protected $artifactsDelegatesFactory;

    /** @var string|int */
    protected $userGuid;

    /**
     * Manager constructor.
     * @param Repository $repository
     * @param Artifacts\Factory $artifactsDelegatesFactory
     */
    public function __construct(
        $repository = null,
        $artifactsDelegatesFactory = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->artifactsDelegatesFactory = $artifactsDelegatesFactory ?: new Artifacts\Factory();
    }

    /**
     * @param int|string $userGuid
     * @return Manager
     */
    public function setUserGuid($userGuid)
    {
        $this->userGuid = $userGuid;
        return $this;
    }

    /**
     * @param string[] $delegates
     * @return bool
     * @throws \Exception
     */
    public function snapshot(array $delegates)
    {
        if (!$this->userGuid) {
            throw new \Exception('Missing User GUID');
        }

        $success = true;

        foreach ($delegates as $delegateClass) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClass);
                $done = $delegate->snapshot($this->userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClass} snapshot failed for {$this->userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param string[] $delegates
     * @return bool
     * @throws \Exception
     */
    public function restore(array $delegates)
    {
        if (!$this->userGuid) {
            throw new \Exception('Missing User GUID');
        }

        $success = true;

        foreach ($delegates as $delegateClass) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClass);
                $done = $delegate->restore($this->userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClass} restore failed for {$this->userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @return \Generator
     * @throws \Exception
     */
    public function getAll()
    {
        if (!$this->userGuid) {
            throw new \Exception('Missing User GUID');
        }

        return $this->repository->getList([
            'user_guid' => $this->userGuid,
        ]);
    }

    /**
     * @param null $type
     * @return bool
     * @throws \Exception
     */
    public function truncate($type = null)
    {
        if (!$this->userGuid) {
            throw new \Exception('Missing User GUID');
        }

        return $this->repository->deleteAll($this->userGuid, $type);
    }
}
