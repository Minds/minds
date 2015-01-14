<?php

$user = elgg_get_page_owner_entity();
$rows = '';

$db = new Minds\Core\Data\Call('entities_by_time');
$count = $db->countRow('object:node:referrer:'.$user->guid);

$rows .= <<< END
				<tr>
					<td class="column-one"><b>Referrals:</b></td>
					<td>$count</td>
				</tr>
END;
$rows .= <<< END
				<tr>
					<td class="column-one"><b>Earnings:</b></td>
					<td>$0.00</td>
				</tr>
END;

$title = elgg_echo('Referrals');
$content = "<table class=\"elgg-table-alt\">$rows</table>";

echo elgg_view_module('info', $title, $content);
