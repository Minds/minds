<h3 <?php echo $emailStyles->getStyles('m-fonts', 'm-newsfeedSidebar__header'); ?>>Suggested channels</h3>
<table <?php echo $emailStyles->getStyles('m-suggestions__sidebar', 'm-clear'); ?> cellspacing="0">
    <?php $counter = 0; ?>
    <?php foreach($vars['suggestions'] as $index=>$suggestion): ?>
    <?php
    $entity = $suggestion->getEntity();
    if (!$entity) {
        continue;
    }
    $avatarUrl =  "{$entity->getIconUrl()}";
    $channelUrl = "{$vars['site_url']}{$entity->get('name')}?{$vars['tracking']}";
    $rowStyle = 'm-suggestionsSidebarList__item';
    if(++$counter == count($vars['suggestions'])) {
        $rowStyle = '';
    }
    ?>
    <tr id="suggested-channel-<?php echo $entity->getGuid(); ?>">
        <td <?php echo $emailStyles->getStyles($rowStyle, 'm-avatar-size', 'm-clear'); ?>>
        <a href="<?php echo $channelUrl ?>">
            <img alt="<?php echo $entity->get('name'); ?>" <?php echo $emailStyles->getStyles('m-suggestionsSidebarListItem__avatar'); ?> src="<?php echo "{$vars['site_url']}api/v2/media/magicproxy?size=28&amp;src={$avatarUrl}&amp;roundX=28&amp;roundY=28"; ?>"/>
        </a>
        </td>
        <td <?php echo $emailStyles->getStyles($rowStyle); ?>>
        <h4 <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-newsfeedSidebar__header'); ?>>
            <a <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-link', 'm-newsfeedSidebar__header'); ?> href="<?php echo $channelUrl ?>">
                <?php echo $entity->get('name'); ?>
            </a>
        </h4>
        <p <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-suggestionsSidebarListItem__description'); ?>>
            <a <?php echo $emailStyles->getStyles('m-clear', 'm-fonts', 'm-link', 'm-suggestionsSidebarListItem__description'); ?> href="<?php echo $channelUrl ?>">
                <?php echo $entity->get('briefdescription'); ?>
            </a>
        </p>
        </td>
    </tr>
    <?php endforeach ?>
</table>
