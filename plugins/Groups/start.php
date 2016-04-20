<?php
/**
 * Minds Groups
 */
namespace Minds\Plugin\Groups;

use Minds\Components;
use Minds\Core;
use Minds\Api;
use Minds\Entities\Factory as EntityFactory;

use Minds\Plugin\Groups\Core\SEO;
use Minds\Plugin\Groups\Core\Navigation;
use Minds\Plugin\Groups\Core\Events;
use Minds\Api\Routes;

class start extends Components\Plugin
{
    public function __construct()
    {
        SEO::setup();
        Navigation::setup();
        Events::setup();

        Routes::add('v1/groups', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\groups');
        Routes::add('v1/groups/group', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\group');
        Routes::add('v1/groups/membership', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\membership');
        Routes::add('v1/groups/notifications', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\notifications');
        Routes::add('v1/groups/invitations', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\invitations');
        Routes::add('v1/groups/management', '\\Minds\\Plugin\\Groups\\Controllers\\api\\v1\\management');

        // TODO: [emi] Add FS route for icon.php
    }
}
