<?php
/**
 * summon
 *
 * @author edgebal
 */

namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Repository as ReportsRepository;
use Minds\Core\Reports\Summons\Manager;
use Minds\Core\Reports\Summons\Summon as SummonEntity;
use Minds\Core\Session;
use Minds\Interfaces;

class summon implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function post($pages)
    {
        $reportUrn = $_POST['report_urn'] ?? null;
        $juryType = $_POST['jury_type'] ?? null;
        $userGuid = Session::getLoggedInUserGuid();
        $status = $_POST['status'] ?? null;

        /** @var Manager $summonsManager */
        $summonsManager = Di::_()->get('Moderation\Summons\Manager');

        /** @var ReportsRepository $reportsRepository */
        $reportsRepository = Di::_()->get('Reports\Repository');

        $summon = new SummonEntity();
        try {
            $summon
                ->setReportUrn($reportUrn)
                ->setJuryType($juryType)
                ->setJurorGuid((string) $userGuid)
                ->setStatus($status);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }

        if (!$summonsManager->isSummoned($summon)) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You\'re not summoned',
            ]);
        }

        $summonsManager->respond($summon);

        $response = [
            'summon' => $summon->getStatus(),
            'expires_in' => $summon->getTtl(),
        ];

        if ($summon->isAccepted()) {
            $response['report'] = $reportsRepository->get($summon->getReportUrn());
        }

        return Factory::response($response);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
