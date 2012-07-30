<?php
/**
 * Individual image RSS view
 *
 * @uses $vars['entity'] TidypicsImage
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$permalink = htmlspecialchars($vars['entity']->getURL(), ENT_NOQUOTES, 'UTF-8');
$pubdate = date('r', $vars['entity']->getTimeCreated());

$title = $vars['entity']->getTitle();
$description = autop($vars['entity']->description);

$creator = elgg_view('page/components/creator', $vars);
$georss = elgg_view('page/components/georss', $vars);
$extension = elgg_view('extensions/item', $vars);

$thumbnail_url = $vars['entity']->getIconURL('tiny');
$download_url = $vars['entity']->getIconURL('large');

$mime_type = $vars['entity']->getMimeType();

$item = <<<__HTML
<item>
	<guid isPermaLink="true">$permalink</guid>
	<pubDate>$pubdate</pubDate>
	<link>$permalink</link>
	<title><![CDATA[$title]]></title>
	<description><![CDATA[$description]]></description>
	$creator$georss$extension
	<media:content url="$download_url" medium="image" type="$mime_type" />
	<media:title><![CDATA[$title]]></media:title>
	<media:description><![CDATA[$description]]></media:description>
	<media:thumbnail url="$thumbnail_url"></media:thumbnail>
</item>

__HTML;

echo $item;
