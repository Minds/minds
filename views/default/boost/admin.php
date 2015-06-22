<?php
/**
 * Admin view for boost accepting
 */
 
$entities = $vars['entities'];
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : "Newsfeed";

echo "<a href=\"". elgg_get_site_url() . "boost/admin?type=Newsfeed\">Newsfeed</a> | <a href=\"". elgg_get_site_url() . "boost/admin?type=Suggested\">Suggested</a>";

echo "<p>Showing 12 of (" . $vars['remaining'] . ") boosts</p>";

echo "<ul class=\"elgg-list x1 boost list-newsfeed\">";

foreach($entities as $entity){
    echo "<li class=\"elgg-item\" id=\"$entity->guid\">";

    echo elgg_view_entity($entity);
    echo "<p>Impressions: $entity->boost_impressions </p>";
    
    echo "<form method=\"POST\" action=\"" . elgg_get_site_url() . "boost/admin?type=$type\">";
        echo "<input type=\"hidden\" name=\"type\" value=\"$type\">";
        echo "<input type=\"hidden\" name=\"guid\" value=\"$entity->guid\">";
        echo "<input type=\"hidden\" name=\"impressions\" value=\"$entity->boost_impressions\">";
        echo "<input type=\"submit\" name=\"accept\" value=\"Accept\" class=\"button accept-button\">";
        echo "<input type=\"submit\" name=\"reject\" value=\"Reject\" class=\"button reject-button\">";
         echo "<input type=\"hidden\" name=\"action\" value=\"\" id=\"action\">";
        echo elgg_view('input/securitytoken');
    echo "</form>";
    
    echo "</li>";
}
echo "</ul>";

?>
<div class="load-more-boosts" data-load-next="<?= end($entities)->guid ?>"></div>
