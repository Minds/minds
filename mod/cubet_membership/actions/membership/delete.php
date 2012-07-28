<?php
    /**
    * Elgg Membership plugin
    * Membership membership delete page
    * @package Elgg Membership plugin
    * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
    * @author Cubet Technologies
    * @copyright Cubet 2010
    * @link http://elgghub.com/
    */

    // Make sure we're logged in (send us to the front page if not)
    gatekeeper();

    // Get input data
    $guid =  get_input('guid');

    // Make sure we actually have permission to edit
    $membership = get_entity($guid);

    if ($membership->getSubtype() == "premium_membership" && $membership->canEdit()) {
        // Delete it!
        $rowsaffected = $membership->delete();
        if ($rowsaffected > 0) {
            // Success message
            system_message(elgg_echo("membership:deleted"));
        } else {
            register_error(elgg_echo("membership:notdeleted"));
        }
        // Forward to the main blog page
        forward($CONFIG->wwwroot."membership/settings/premium");
    }
		
?>