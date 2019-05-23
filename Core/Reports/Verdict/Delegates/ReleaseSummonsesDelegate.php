<?php
/**
 * ReleaseSummonsesDelegate
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Verdict\Delegates;

use Exception;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Summons\Manager;
use Minds\Core\Reports\Verdict\Verdict;

class ReleaseSummonsesDelegate
{
    /** @var Manager */
    protected $summonsManager;

    /**
     * ReleaseSummonsesDelegate constructor.
     * @param Manager $summonsManager
     */
    public function __construct(
        $summonsManager = null
    )
    {
        $this->summonsManager = $summonsManager ?: Di::_()->get('Moderation\Summons\Manager');
    }

    /**
     * @param Verdict $verdict
     * @throws Exception
     */
    public function onCast(Verdict $verdict)
    {
        $juryType = $verdict->isAppeal() ? 'appeal_jury' : 'initial_jury';

        $this->summonsManager->release(
            $verdict->getReport()->getUrn(),
            $juryType
        );
    }
}
