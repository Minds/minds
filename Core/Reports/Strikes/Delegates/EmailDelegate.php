<?php
/**
 * Email Notification delegate for Strikes
 */
namespace Minds\Core\Reports\Strikes\Delegates;

use Minds\Core\Di\Di;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Events\EventsDispatcher;
use Minds\Common\Urn;

class EmailDelegate
{
    /** @var Custom $campaign */
    protected $campaign;

    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;

    /** @var Urn $urn */

    public function __construct($campaign = null, $entitiesBuilder = null, $urn = null)
    {
        $this->campaign = $campaign ?: new Custom;
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->urn = $urn ?: new Urn;
    }

    /**
     * On Strike
     * @param Strike $strike 
     * @return void
     */
    public function onStrike(Strike $strike)
    {
        $entityUrn = $strike->getReport()->getEntityUrn();
        $entityGuid = $this->urn->setUrn($entityUrn)->getNss();

        $entity = $this->entitiesBuilder->single($entityGuid);



    }

}
