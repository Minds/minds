<?php
/**
 * ArtifactsDelegateInterface.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;


interface ArtifactsDelegateInterface
{
    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function snapshot($userGuid);

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function restore($userGuid);

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function hide($userGuid);

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function delete($userGuid);
}
