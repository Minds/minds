<?php
/**
 * Action delegate for Verdicts
 */
namespace Minds\Core\Reports\Verdict\Delegates;

use Minds\Core\Security\ACL;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Di\Di;

class ActionDelegate
{
    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var Actions $actions */
    private $actions;

    public function __construct(
        $entitiesBuilder = null,
        $actions = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
        $this->actions = $actions ?: Di::_()->get('Reports\Actions');
    }

    public function onAction(Verdict $verdict)
    {
        // Disable ACL 
        ACL::$ignore = true;
        $entityGuid = $verdict->getReport()->getEntityGuid();

        $entity = $this->entitiesBuilder->single($entityGuid);

        switch ($verdict->getAction()) {
            case '1.1':
            case '1.2': // Should be fully removed?
            case '1.3':
            case '1.4':
                $this->actions->setDeletedFlag($entity, true);
                break;
            case '2.1':
            case '2.2':
            case '2.4':
                //Mark as explicit
                $nsfw = explode('.', $verdict->getAction())[1];
                $entity->setNsfw([$nsfw]);
                $entity->save();
                break;
            case 'uphold':
            case 'overturn':
                break;
            default: //Remove is the default
                $this->actions->setDeletedFlag($entity, true);
                break;
        }

        // Enable ACL again
        ACL::$ignore = false;
    }

}