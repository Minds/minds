<?php
/**
 * MetricsDelegate
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Verdict\Delegates;

use Exception;
use Minds\Core\Analytics\Metrics\Event;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Entities\User;

class MetricsDelegate
{
    /**
     * @param Verdict $verdict
     * @throws Exception
     */
    public function onCast(Verdict $verdict)
    {
        if (!$verdict->isAppeal()) {
            return; // No need to record this
        }

        $decisions = $verdict->isAppeal() ?
            $verdict->getReport()->getAppealJuryDecisions() :
            $verdict->getReport()->getInitialJuryDecisions();

        $jurorGuids = array_map(function(Decision $decision) {
            return $decision->getJurorGuid();
        }, $decisions);

        foreach ($jurorGuids as $jurorGuid) {
            $juror = new User($jurorGuid);

            $event = new Event();
            $event
                ->setType('action')
                ->setAction('jury_duty')
                ->setProduct('platform')
                ->setUserGuid($juror->guid)
                ->setUserPhoneNumberHash($juror->getPhoneNumberHash())
                ->setEntityGuid($verdict->getReport()->getUrn())
                ->push();
        }
    }
}
