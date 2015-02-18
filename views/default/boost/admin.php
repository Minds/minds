<?php
/**
 * Admin view for boost accepting
 */
 
$entities = $vars['entities'];

foreach($entities as $entity){
    echo "<li>";
    echo elgg_view_entity($entitiy);
    echo "<p>Impressions: $entity->boost_impressions </p>";
    
    echo "<form method=\"POST\" action=\"" . elgg_get_site_url() . "boost/admin\">";
        echo "<input type=\"hidden\" name=\"guid\" value=\"$entity->guid\">";
         echo "<input type=\"hidden\" name=\"impressions\" value=\"$entity->boost_impressions\">";
        echo "<input type=\"submit\" >";
        echo elgg_view('input/securitytoken');
    echo "</form>";
    
    echo "</li>";
}
    